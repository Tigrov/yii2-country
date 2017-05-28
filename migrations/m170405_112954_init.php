<?php

use yii\db\Migration;

class m170405_112954_init extends Migration
{
    const COUNTRY_CSV = __DIR__ . '/data/country.csv';
    const DIVISION_CSV = __DIR__ . '/data/division.csv';
    const DIVISION_TRANSLATION_CSV = __DIR__ . '/data/division_translation.csv.gz';
    const CITY_CSV = __DIR__ . '/data/city.csv.gz';
    const CITY_TRANSLATION_CSV = __DIR__ . '/data/city_translation.csv.gz';

    const CSV_DELIMITER = ';';
    const INSERT_ROWS = 10000;

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName == 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%country}}', $this->getCountryFields(), $tableOptions);
        $this->loadFromCsv('{{%country}}', array_keys($this->getCountryFields()), static::COUNTRY_CSV);
        $this->addPrimaryKey('country_pkey', '{{%country}}', ['code']);

        $this->createTable('{{%division}}', $this->getDivisionFields(), $tableOptions);
        $this->loadFromCsv('{{%division}}', array_keys($this->getDivisionFields()), static::DIVISION_CSV);
        $this->addPrimaryKey('division_pkey', '{{%division}}', ['country_code', 'division_code']);

        $this->createTable('{{%division_translation}}', $this->getDivisionTranslationFields(), $tableOptions);
        $this->loadFromCsv('{{%division_translation}}', array_keys($this->getDivisionTranslationFields()), static::DIVISION_TRANSLATION_CSV);
        $this->addPrimaryKey('division_translation_pkey', '{{%division_translation}}', ['country_code', 'division_code', 'language_code']);

        $this->createTable('{{%city}}', $this->getCityFields(), $tableOptions);
        $this->loadFromCsv('{{%city}}', array_keys($this->getCityFields()), static::CITY_CSV);
        $this->addPrimaryKey('city_pkey', '{{%city}}', ['geoname_id']);
        $this->createIndex('city_country_code_division_code', '{{%city}}', ['country_code', 'division_code']);

        $this->createTable('{{%city_translation}}', $this->getCityTranslationFields(), $tableOptions);
        $this->loadFromCsv('{{%city_translation}}', array_keys($this->getCityTranslationFields()), static::CITY_TRANSLATION_CSV);
        $this->addPrimaryKey('city_translation_pkey', '{{%city_translation}}', ['geoname_id', 'language_code']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%division}}');
        $this->dropTable('{{%division_translation}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%city_translation}}');
    }

    public function loadFromCsv($tableName, $columns, $csvFile)
    {
        if (pathinfo($csvFile, PATHINFO_EXTENSION) === 'gz') {
            $csvFile = static::ungzip($csvFile);
        }

        echo "    > load into $tableName from $csvFile ...";
        $time = microtime(true);

        try {
            switch ($this->db->driverName) {
                case 'pgsql':
                    $this->db
                        ->createCommand("COPY $tableName FROM '$csvFile' DELIMITER '" . static::CSV_DELIMITER . "' QUOTE '\"' ESCAPE '\"' CSV")
                        ->execute();
                    break;
                case 'mssql':
                    $this->db
                        ->createCommand("BULK INSERT $tableName FROM '$csvFile' WITH(FIELDTERMINATOR='" . static::CSV_DELIMITER . "', TABLOCK)")
                        ->execute();
                    break;
                case 'mysql':
                case 'oracle':
                default:
                    $this->db
                        ->createCommand("LOAD DATA INFILE '$csvFile' INTO TABLE $tableName FIELDS TERMINATED BY '" . static::CSV_DELIMITER . "' ENCLOSED BY '\"' ESCAPED BY '\"'")
                        ->execute();
            }

            echo ' done (time: ' . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        } catch (\Exception $e) {
            echo " filed: " . $e->getMessage() . PHP_EOL;
            echo "    > trying batch insert ...\n";

            $csv = fopen($csvFile, 'r');

            do {
                $rows = [];
                for ($i = 0; ($row = fgetcsv($csv, 1024, static::CSV_DELIMITER)) && $i < static::INSERT_ROWS; ++$i) {
                    $rows[] = $row;
                }

                $this->batchInsert($tableName, $columns, $rows);
            } while ($row);

            fclose($csv);
        }
    }

    public static function ungzip($file)
    {
        // Remove .gz from the file name
        $outFile = substr($file, 0, -3);

        // Open files in binary mode
        $gz = gzopen($file, 'rb');
        $out = fopen($outFile, 'wb');

        while(!gzeof($gz)) {
            fwrite($out, gzread($gz, 4096));
        }

        fclose($out);
        gzclose($gz);
    }

    public function getCountryFields()
    {
        return [
            'code' => $this->char(2)->notNull(),
            'geoname_id' => $this->integer()->notNull(),
            'capital_geoname_id' => $this->integer()->notNull(),
            'language_code' => $this->string(3)->notNull(),
            'currency_code' => $this->char(3)->notNull(),
            'timezone_code' => $this->string(50)->notNull(),
            'latitude' => $this->decimal(10, 8)->notNull(),
            'longitude' => $this->decimal(11, 8)->notNull(),
            'name_en' => $this->string(100)->notNull(),
        ];
    }

    public function getDivisionFields()
    {
        return [
            'country_code' => $this->char(2)->notNull(),
            'division_code' => $this->string(3)->notNull(),
            'geoname_id' => $this->integer(),
            'capital_geoname_id' => $this->integer(),
            'language_codes' => $this->string(),
            'timezone_code' => $this->string(50),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'name_en' => $this->string(100)->notNull(),
        ];
    }

    public function getCityFields()
    {
        return [
            'geoname_id' => $this->integer()->notNull(),
            'country_code' => $this->char(2)->notNull(),
            'division_code' => $this->string(3),
            'timezone_code' => $this->string(50),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'name_en' => $this->string(200)->notNull(),
        ];
    }

    public function getDivisionTranslationFields()
    {
        return [
            'country_code' => $this->char(2)->notNull(),
            'division_code' => $this->string(3)->notNull(),
            'language_code' => $this->string(25)->notNull(),
            'value' => $this->text()->notNull(),
        ];
    }

    public function getCityTranslationFields()
    {
        return [
            'geoname_id' => $this->integer()->notNull(),
            'language_code' => $this->string(25)->notNull(),
            'value' => $this->text()->notNull(),
        ];
    }
}
