<?php

namespace App\Service;

class YearGeneratorService
{
  public function getYearsList(): array
  {
    $currentYear = (int) date('Y');
    $years = range(1900, $currentYear);

    return array_combine($years, $years);
  }
}
