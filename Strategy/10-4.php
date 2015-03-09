<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface Sorter {
    public function sort($data);
}

class SelectionSorter implements Sorter {
    public function sort($data) {
        for ($i = 0; $i < count($data); $i++) {
            for ($j = $i + 1; $j < count($data); $j++) {
                if ($data[$i].)
            }
        }
    }
}