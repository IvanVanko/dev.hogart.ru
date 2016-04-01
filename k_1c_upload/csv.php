<?php

    class csv {
        private $countQueryCsv = 0;
        private $breandFile;
        private $setFile;
        private $categoryFile;
        private $propnameFile;
        private $propFile;
        private $tehdocFile;
        private $priceFile;
        private $warehouseFile;
        private $item_amountFile;
        private $unit_messure_catalogFile;
        private $unit_messureFile;
        private $itemFile;
        private $item_propFile;
        public  $create_dir;
        public  $dirLogError;
        public  $statedir;

        function __construct($create_dir) {
            $this->create_dir = $create_dir;
            $time             = date("Y-m-d-H-i-s");
            $pr               = 'cache/';
            if ($create_dir) {
                mkdir('cache/'.$create_dir);
                $pr .= $create_dir.'/';
            }
            $this->breandFile               = $pr.'brand-'.$time.'.csv';
            $this->setFile                  = $pr.'set-'.$time.'.csv';
            $this->categoryFile             = $pr.'category-'.$time.'.csv';
            $this->propnameFile             = $pr.'propname-'.$time.'.csv';
            $this->propFile                 = $pr.'prop-'.$time.'.csv';
            $this->tehdocFile               = $pr.'tehdoc-'.$time.'.csv';
            $this->priceFile                = $pr.'price-'.$time.'.csv';
            $this->warehouseFile            = $pr.'warehouse-'.$time.'.csv';
            $this->item_amountFile          = $pr.'item_amount-'.$time.'.csv';
            $this->unit_messure_catalogFile = $pr.'unit_messure_catalog-'.$time.'.csv';
            $this->unit_messureFile         = $pr.'unit_messure-'.$time.'.csv';
            $this->itemFile                 = $pr.'item-'.$time.'.csv';
            $this->item_propFile            = $pr.'item_prop-'.$time.'.csv';
            $this->dirLogError              = 'cache/'.'log-'.$time.'.csv';
            $this->statedir                 = 'cache/'.'state.csv';
        }

        function resetQueryCsv() {
            $this->countQueryCsv = 0;
        }

        function saveState($key) {
            $handle = fopen($this->statedir, "a+");
            fputcsv($handle, array($key, time()), ';');
            $this->countQueryCsv++;
            fclose($handle);
        }

        function dynamicSave($file, $array = array()){
            $handle = fopen($file, "a+");
            $array[]= date("d.m.Y G:i");
            fputcsv($handle, $array, ';');
            $this->countQueryCsv++;
            fclose($handle);
        }

        function getArrayState() {
            $handle = fopen($this->statedir, "r");
            if($handle!== FALSE){
                $arr = array();
                while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                    $arr[$data[0]] = date("d.m.Y G:i", $data[1]);
                }
                fclose($handle);
                return $arr;
            }
            else{
                return array();
            }
        }

        function saveLog($lines) {
            $handle = fopen($this->dirLogError, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveBrands($lines) {
            $handle = fopen($this->breandFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveSet($lines) {
            $handle = fopen($this->setFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveCategory($lines) {
            $handle = fopen($this->categoryFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function savePropname($lines) {
            $handle = fopen($this->propnameFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveProp($lines) {
            $handle = fopen($this->propFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveTehdoc($lines) {
            $handle = fopen($this->tehdocFile, "a+");
            foreach ($lines as $line) {
                $tmp         = (array) $line->lines;
                $line->lines = 'object';
                $line        = (array) $line;
                $line        = $line + $tmp;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function savePrice($lines) {
            $handle = fopen($this->priceFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }


        function saveWarehouse($lines) {
            $handle = fopen($this->warehouseFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveItem_amount($lines) {
            $handle = fopen($this->item_amountFile, "a+");
            foreach ($lines as $line) {
                $tmp                    = (array) $line->item_amount_line;
                $line->item_amount_line = 'object';
                $line                   = (array) $line;
                $line                   = $line + $tmp;
                $line                   = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveUnit_messure_catalog($lines) {
            $handle = fopen($this->unit_messure_catalogFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveUnit_messure($lines) {
            $handle = fopen($this->unit_messureFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveItem($lines) {
            $handle = fopen($this->itemFile, "a+");
            foreach ($lines as $line) {
                $tmp = array(array('name' => $line->id));
                $tmp = $tmp + $line->properties;
                $this->saveItem_prop($tmp);
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }

        function saveItem_prop($lines) {
            $handle = fopen($this->item_propFile, "a+");
            foreach ($lines as $line) {
                $line = (array) $line;
                fputcsv($handle, $line, ';');
                $this->countQueryCsv++;
            }
            fclose($handle);
        }


    }