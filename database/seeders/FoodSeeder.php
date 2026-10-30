<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodCategory;
use App\Models\Food;
use Illuminate\Support\Str;
use ZipArchive;

class FoodSeeder extends Seeder
{
    /**
     * Seed the foods and food_categories tables from the Excel / JSON dataset.
     */
    public function run(): void
    {
        $rows = $this->loadFromExcelOrJson();

        if (empty($rows)) {
            $this->command?->warn('No se encontraron registros de alimentos para importar.');
            return;
        }

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Food::truncate();
        FoodCategory::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $categoryCache = [];

        foreach ($rows as $row) {
            $catName = trim($row['category'] ?? '');
            $name = trim($row['name'] ?? '');
            if ($catName === '' || $name === '') {
                continue;
            }

            $slug = Str::slug($catName);
            if (!isset($categoryCache[$slug])) {
                $cat = FoodCategory::updateOrCreate(
                    ['slug' => $slug],
                    ['name' => $catName]
                );
                $categoryCache[$slug] = $cat->id;
            }

            $categoryId = $categoryCache[$slug];
            $subcategory = trim($row['subcategory'] ?? '') ?: null;
            $state = trim($row['state'] ?? '') ?: null;
            $portionType = trim($row['portion_type'] ?? '100 g');
            $portionDetail = trim($row['portion_detail'] ?? '') ?: null;

            [$baseQty, $baseUnit] = $this->parsePortionType($portionType, $portionDetail);
            $gramWeight = $this->extractGramWeight($portionDetail, $baseQty);

            Food::updateOrCreate(
                [
                    'food_category_id' => $categoryId,
                    'name' => $name,
                    'state' => $state,
                    'portion_type' => $portionType,
                ],
                [
                    'subcategory' => $subcategory,
                    'portion_detail' => $portionDetail,
                    'base_quantity' => $baseQty,
                    'base_unit' => $baseUnit,
                    'gram_weight' => $gramWeight,
                    'calories' => round((float) ($row['calories'] ?? 0), 2),
                    'protein' => round((float) ($row['protein'] ?? 0), 2),
                    'carbs' => round((float) ($row['carbs'] ?? 0), 2),
                    'fat' => round((float) ($row['fat'] ?? 0), 2),
                ]
            );
        }
    }

    /**
     * Load rows from Excel file if present, otherwise fallback to foods.json.
     */
    protected function loadFromExcelOrJson(): array
    {
        $xlsxPath = database_path('seeders/data/Base_Datos_Alimentos_Macronutrientes-v3.xlsx');
        if (file_exists($xlsxPath) && class_exists(ZipArchive::class)) {
            $parsed = $this->parseExcelMasterSheet($xlsxPath);
            if (!empty($parsed)) {
                return $parsed;
            }
        }

        $jsonPath = database_path('seeders/data/foods.json');
        if (file_exists($jsonPath)) {
            return json_decode(file_get_contents($jsonPath), true) ?: [];
        }

        return [];
    }

    /**
     * Parse the first sheet ("Base Maestra Completa") directly from an .xlsx file.
     */
    public function parseExcelMasterSheet(string $xlsxPath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($xlsxPath) !== true) {
            return [];
        }

        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml !== false) {
            $xml = simplexml_load_string($ssXml);
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } elseif (isset($si->r)) {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                } else {
                    $sharedStrings[] = '';
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $sxml = simplexml_load_string($sheetXml);
        $rows = [];
        $rowIdx = 0;

        foreach ($sxml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $c) {
                $type = (string) $c['t'];
                $val = isset($c->v) ? (string) $c->v : '';
                if ($type === 's') {
                    $val = $sharedStrings[(int) $val] ?? $val;
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $val = (string) $c->is->t;
                }
                $rowData[] = trim($val);
            }

            if (!array_filter($rowData, fn($v) => $v !== '')) {
                continue;
            }

            $rowIdx++;
            // Skip title and header rows
            if ($rowIdx <= 2 || count($rowData) < 10) {
                continue;
            }
            if (mb_strtolower($rowData[0]) === 'categoría' || mb_strtolower($rowData[0]) === 'categoria') {
                continue;
            }

            $rows[] = [
                'category' => $rowData[0],
                'subcategory' => $rowData[1],
                'name' => $rowData[2],
                'state' => $rowData[3],
                'portion_type' => $rowData[4],
                'portion_detail' => $rowData[5],
                'calories' => (float) $rowData[6],
                'protein' => (float) $rowData[7],
                'carbs' => (float) $rowData[8],
                'fat' => (float) $rowData[9],
            ];
        }

        return $rows;
    }

    protected function parsePortionType(string $portionType, ?string $portionDetail): array
    {
        $pt = mb_strtolower(trim($portionType));
        $detail = mb_strtolower(trim((string) $portionDetail));

        if (str_contains($pt, '100')) {
            if (preg_match('/\bml\b/', $detail) && !preg_match('/\bg\b/', $detail)) {
                return [100, 'ml'];
            }
            return [100, 'g'];
        }

        if (str_contains($pt, 'taza')) {
            return [1, 'taza'];
        }

        if (str_contains($pt, 'cda')) {
            return [1, 'cda'];
        }

        if (str_contains($pt, 'pieza') || str_contains($pt, 'pz')) {
            return [1, 'pz'];
        }

        return [1, 'porcion'];
    }

    protected function extractGramWeight(?string $portionDetail, float $defaultQty): ?float
    {
        if (!$portionDetail) {
            return $defaultQty == 100 ? 100.0 : null;
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*(?:g|gr|ml)\b/i', $portionDetail, $matches)) {
            return (float) $matches[1];
        }

        return $defaultQty == 100 ? 100.0 : null;
    }
}

