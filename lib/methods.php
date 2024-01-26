<?php

use Kirby\Content\Field;
use Kirby\Toolkit\Str;

return [
  'toTable' => function (Field $field) {
    $value = $field->value();
    if (!is_string($value)) {
      $value = is_array($value) ? $value : [];
      $headers = !empty($value) ? array_shift($value) : [];
      $rows = $value;
      return ['headers' => $headers, 'rows' => $rows];
    } else {
      $items = Str::split($field, "\n");
      $newRow = [];
      $rows = [];

      foreach ($items as $item) {
        $item = trim($item);

        if ($item === '-') {
          if (!empty($newRow)) {
            $rows[] = $newRow;
            $newRow = [];
          }
        } else {
          $item = trim($item, '- ');
          $item = trim($item, '"\'');
          $newRow[] = $item;
        }
      }

      if (!empty($newRow)) {
        $rows[] = $newRow;
      }

      $blueprint = $field->parent()->blueprint()->field($field->key());
      $hasHeaders = $blueprint['headers'] ?? true;
      $headers = $hasHeaders ? array_shift($rows) : [];

      return ['headers' => $headers, 'rows' => $rows];
    }
  }
];
