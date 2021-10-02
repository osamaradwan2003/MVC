<?php

  namespace Src\Validation\Rules;
  use Illuminate\Contracts\Validation\Rule;
  use Src\Database\Database;
  use Exception;
  class Uniqe
  {
    protected string $message = ":attribute :value has been used";

    protected array $fillableParams = ['table', 'column', 'except'];


    /**
     * @throws Exception
     */
    public function check($value): bool
    {
      $this->requireParameters(['table', 'column']);
      $column = $this->parameter('column');
      $table = $this->parameter('table');
      $except = $this->parameter('except');

      if ($except AND $except == $value) {
        return true;
      }


      $data = Database::table($table)->where($column, '=', $value);
      // true for valid, false for invalid
      return !$data;
    }
  }