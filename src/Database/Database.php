<?php

  namespace Src\Database;
  use PDO;
  use Src\File\File;
  use Src\Http\Request;
  use Src\Url\Url;

  class Database
  {

    /**
     * @var Database
     */
    protected static $instance;

    /**
     * @var PDO
     */
    protected static $connections;

    private static $table;
    private static $statement;
    private static $join;
    private static $where;
    private static $group_by;
    private static $having;
    private static $order_by;
    private static $limet;
    private static $offset;
    private static $whare_bind = [];
    private static $having_bind = [];
    private static $select;
    private static $binding = [];
    private static $setter;

    /**
     * @throws \Exception
     */
    public static function connect(){
      if (! static::$connections){
        $config = File::require_file('config/database.php');
        extract($config);
        /** @noinspection PhpUndefinedVariableInspection */
        $dns = $type . ':dbname=' . $dbname . ';host=' . $host .":" . $port;
        /** @noinspection PhpUndefinedVariableInspection */
        //die($dns);
        $option = [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
          PDO::ATTR_PERSISTENT => false,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'set NAMES ' . $encode . ' COLLATE ' . $collection,
        ];
        try{
          /** @noinspection PhpUndefinedVariableInspection */
          static::$connections = new PDO($dns, $user, $password, $option);
        }catch (\PDOException $e){
          throw new \Exception($e->getMessage());
        }
      }
    }

    /**
     *
     * @return Database
     * @throws \Exception
     */
    private static function instance(): Database
    {
      static::connect();

      if(! static::$instance){
        static::$instance = new Database();
      }
      return self::$instance;
    }

    /**
     * Query function using to execute sql statements or return sql manger functions
     *
     * @param  $query
     *
     * @return Database;
     * @throws \Exception
     * @example Database::query('select * from users');
     * @example Database::query()->select('users')->getAll();
     */

    public static function query($query = null): Database
    {
      self::instance();

      if(! $query){
        if(! self::$table){
          throw new \Exception('Please Select Table');
        }else{
          $query = "SELECT ";
          $query .= self::$select ?? "*";
          $query .= ' FROM ' . self::$table;
          $query .= ' ' . self::$join ?? '';
          $query .= ' ' . self::$where ?? '';
          $query .= ' ' . self::$group_by ?? '';
          $query .= ' ' . self::$having ?? '';
          $query .= ' ' . self::$order_by ?? '';
          $query .= ' ' . self::$limet ?? '';
          $query .= ' ' . self::$offset ?? '';
        }
      }

      self::$statement = $query;
      static::$binding = array_merge(self::$whare_bind, self::$having_bind);
      return self::instance();
    }

    /**
     * @throws \Exception
     */
    public static function select(): Database
    {
      $select = func_get_args();
      $select = implode(' ,', $select);
      self::$select = trim($select) != '' ? $select : '*';
      return self::instance();
    }

    /**
     * @throws \Exception
     */
    public static function table(string $table): Database
    {

      self::$table = $table;
      return self::instance();
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     * @return Database
     * @throws \Exception
     */
    public static function join(string $table, string $first, string $operator, string $second, string $type='INNER'): Database
    {
      $join = ' ' . strtoupper($type) . ' JOIN ' . $table . ' ON ' . $first .  ' ' . $operator . ' '. $second . ' ';
      self::$join = $join;
      return self::instance();
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return Database
     * @throws \Exception
     */
    public static function LJoin(string $table, string $first, string $operator, string $second): Database
    {
      self::join( $table,  $first,  $operator,  $second, 'LEFT');
      return self::instance();
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return Database
     * @throws \Exception
     */
    public static function RJoin(string $table, string $first, string $operator, string $second): Database
    {
      self::join( $table,  $first,  $operator,  $second, 'RIGHT');
      return self::instance();
    }

    /**
     * @throws \Exception
     */
    public static function where(string $column, string $operator, mixed $value, string $type=null): Database
    {
      $where = ' `' . $column . '` ' . $operator .  ' ? ' ;
      $stmt = '';
      if(! self::$where){
        $stmt = ' WHERE '. $where;
      }else{
        if(!$type){
          $stmt .= ' AND ' . $where;
        }else{
          $stmt .= ' ' . $type . ' ' . $where;
        }
      }
      self::$where .= $stmt;
      self::$whare_bind[] = htmlspecialchars($value);
      return self::instance();
    }

    /**
     * @throws \Exception
     */
    public static function orWhere(string $column, string $operator, mixed $value): Database{
      return self::where($column, $operator, $value, 'OR');
    }

    /**
     * @throws \Exception
     */
    public static function groupBy(): Database
    {
      $groupBy = func_get_args();
      $groupBy = ' GROUP BY ' . implode(' ,', $groupBy) . ' ';

      static::$group_by = $groupBy;
      return self::instance();
    }

    /**
     *
     * @throws \Exception
     */
    public static function having(string $column, string $operator, mixed $value): Database
    {
      $having = ' `' . $column . '` ' . $operator .  ' ? ';
      $stmt = '';
      if(! self::$having){
        $stmt = 'HAVING'. $having;
      }else{
        $stmt .= ' AND ' . $having;
      }
      self::$having .= $stmt;
      self::$having_bind[] = htmlspecialchars($value);
      return self::instance();
    }

    /**
     *
     * @param string $column
     * @param string $type
     * @return  Database
     *@throws \Exception
     */
    public static function orderBy(string $column, string $type): Database
    {
      $sep = static::$order_by ? ' ,' : "ORDER BY";
      $type = strtoupper($type);
      $type = ($type != null && in_array(['ASC', 'DESC'])) ? $type : 'ASC';
      $stmt = $sep . ' ' . $column . ' ' . $type;
      static::$order_by .= $stmt;
      return self::instance();
    }

    /**
     * @param string $limit
     *
     * @throws \Exception
     * @return  Database
     */
    public static function limit(string $limit): Database
    {
      self::$limet = ' LIMIT ' . $limit . ' ';
      return self::instance();
    }

    /**
     * @param string $offset
     * @throws \Exception
     * @return  Database
     */
    public static function offset(string $offset): Database
    {
      self::$offset = ' OFFSET ' . $offset . ' ';
      return self::instance();
    }

    /**
     * @throws \Exception
     */
    private static function fetchExecute(): bool|\PDOStatement
    {
        static::query(static::$statement);
        $q = trim(self::$statement, ' ');
        $data = static::$connections->prepare($q);
        //var_dump($data);
        $data->execute(static::$binding);
        static::clear();
        return $data;
    }

    /**
     * @throws \Exception
     */
    public static function  get(): array
    {
      $data = self::fetchExecute();
      return $data->fetchAll();
    }
    /**
     * @throws \Exception
     */
    public static function  first(): mixed
    {
      $data = self::fetchExecute();
      return $data->fetch();
    }


    /**
     * @throws \Exception
     */
    private static function execute(array $data, string $q, bool $where){
        $where = $where ?? false;
        self::instance();
        if(! static::$table){
          throw new \Exception('Please select database table');
        }

        foreach ($data as $key => $val){
          static::$setter .= ' `' . $key . '` = ?, ';
          static::$binding[] = filter_var($val , FILTER_SANITIZE_STRING);
        }

        static::$setter = trim(self::$setter, ', ');
        $q .= static::$setter;
        $q .= ($where != false) ? static::$where :'';
        self::$binding = ($where != false) ? array_merge( self::$binding, self::$whare_bind): self::$binding;

        $data = self::$connections->prepare($q);
        $data->execute(self::$binding);

        self::clear();

    }


    /**
     * @throws \Exception
     */
    public static function insert(array $data, string $column_fingerPrint = 'id'){
      $table = self::$table;
      $q = 'INSERT INTO ' .$table . ' SET ';
      self::execute($data, $q, false);
      $obj_id = self::$connections->lastInsertId();

      //echo self::getQuery();

      echo '<br>';

      //return self::table($table)->where($column_fingerPrint , ' = ', $obj_id)->first();
    }

    /**
     * @throws \Exception
     */
    public static function update(array $data):bool{
      $table = self::$table;
      $q = ' UPDATE ' .$table . ' SET ';

      self::execute($data, $q, true);

      return true;

    }

    /**
     * @throws \Exception
     */
    public static function delete():bool{
      $table = self::$table;
      $q = ' DELETE FROM ' .$table . '  ';
      self::execute([], $q, true);

      return true;

    }


    public static function pagenation($item_per_page=15, $req_key = 'page'){
      static::query(static::$statement);
      $q = trim(self::$statement, ' ');
      $data = self::$connections->prepare($q);
      $data->execute();
      $pages = ceil(($data->rowCount() / $item_per_page));
      $page = Request::all()[$req_key] ?? $req_key;
      $curr_page = (! is_numeric($page) || $page < 1) ? "1" : $page ;
      $offset = ($curr_page - 1) * $item_per_page;
      static::limit($item_per_page);
      static::offset($offset);
      static::query();

      $data = self::fetchExecute();
      $res = $data->fetchAll();
      return ['items' => $res, 'pageNum'=>$page, 'pageCount'=>$pages, 'item_per_page' => $item_per_page];
    }

    public static function get_links($pages_num, $req_key = 'page', $from = 2, $to = 2, $is_numric=false, $path=''){
      $links = '';
      $currnt = Request::all()[$req_key] ?? $req_key;
      //die($currnt);
      $from = $currnt - $from;
      $to = $currnt + $to;
      if($from < 2) {
        $from = 2;
        $to = $from + 4;
      }
      if($to >= $pages_num){
        $diff = $to - $pages_num + 1;
        $to = $pages_num - 1;
        $from = ($from > 2) ? $from - $diff : 2;
      }
      if(!$is_numric){
        $links .= '<ul class="pagination_list">';
        $full_link = Request::getBaseUrl() . Request::getUri();
        $full_link = preg_replace('/\?' . $req_key . '=(.*)/', '', $full_link);
        $full_link = preg_replace('/\&' . $req_key . '=(.*)/', '', $full_link);
        $currnt_active = $currnt == 1 ? 'active' : "";
        $href = strpos($full_link, "?") ? $full_link . "&". $req_key . '=1' :  $full_link . "?". $req_key . '=1';
        $links .= "\n <li class='link_item'> \n <a href=' ". $href ."' class='link ". $currnt_active ."'> 1 </a> \n </li> \n";

        for($i=$from; $i<= $to; $i++){
          $currnt_active = $currnt == $i ? 'active' : "";
          $href = strpos($full_link, "?") ? $full_link . "&". $req_key . '='. $i. '' :  $full_link . "?".
            $req_key . '=' . $i;
          $links .= "\n <li class='link_item'> \n <a href=' ". $href ."' class='link ". $currnt_active ."'> ". $i ." </a> \n </li> \n";
        }

        $currnt_active = $currnt == $pages_num ? 'active' : "";
        $href = strpos($full_link, "?") ? $full_link . "&". $req_key . '='. $pages_num. '' :  $full_link . "?".
          $req_key . '=' . $pages_num;
        $links .= "\n <li class='link_item'> \n <a href=' ". $href ."' class='link ". $currnt_active ."'> ". $pages_num
          ."</a> \n </li> \n";
      }else{
        $links .= '<ul class="pagination_list"> ';
        $full_link = Url::getPath($path);
        $currnt_active = $currnt == 1 ? 'active' : "";
        $href = $full_link . '/' . 1;
        $links .= "\n <li class='link_item'> \n  <a href=' ". $href ."' class='link ". $currnt_active ."'> 1 </a> \n </li> \n";

        for($i=$from; $i<= $to; $i++){
          $currnt_active = $currnt == $i ? 'active' : "";
          $href = $full_link . '/' . $i;
          $links .= "\n <li class='link_item'>\n  <a href=' ". $href ."' class='link ". $currnt_active ."'> ". $i ." </a> \n </li> \n";
        }
        $currnt_active = $currnt == $pages_num ? 'active' : "";
        $href =$full_link . '/' . $pages_num;
        $links .= "\n <li class='link_item'> \n <a href=' ". $href ."' class='link ". $currnt_active ."'> ". $pages_num
          ." </a> \n </li> \n";
      }
      $links .= "\n </ul> \n";
      return $links;
    }


    private static function clear(){
      static::$table = '';
      static::$statement = '';
      static::$join = '';
      static::$where = '';
      static::$group_by = '';
      static::$having = '';
      static::$order_by = '';
      static::$limet = '';
      static::$offset = '';
      static::$whare_bind = [];
      static::$having_bind = [];
      static::$select = '';
      static::$binding = [];
      static::$setter = '';
    }

    /**
     * @throws \Exception
     */
    public static function getQuery(): string
    {
      self::query();
      return self::$statement;
    }

  }