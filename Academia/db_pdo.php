<?php

/**
 * Clase DB
 * Esta clase es un wrapper de la librería de funciones db_pdo. 
 * Utiliza el patrón Singleton para devolver siempre la misma instancia
 */
class DB
{
    private static $instancia = null;
    private $conn = null;

    /**
     * __construct
     *
     * @param  mixed $conn
     * @return void
     */
    private function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * getConnection
     *
     * @return DB|false
     */
    public static function open($conf = null): DB|false
    {
        if (self::$instancia == null) {
            $conn = db_open($conf);
            if ($conn) {
                self::$instancia = new DB($conn);
            } else {
                return false;
            }
        }

        return self::$instancia;
    }

    /**
     * begin
     *
     * @return void
     */
    public function begin()
    {
        db_begin($this->conn);
    }

    /**
     * commit
     *
     * @return void
     */
    public function commit()
    {
        db_commit($this->conn);
    }

    /**
     * query
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @return void
     */
    public function query($sql, $params = null)
    {
        return db_query($this->conn, $sql, $params);
    }

    /**
     * get_by_id
     *
     * @param  mixed $table
     * @param  mixed $id
     * @return void
     */
    public function get_by_id($table, $id)
    {
        return db_get_by_id($this->conn, $table, $id);
    }

    public function filter($table, $filtro, $orden_campo, $orden_dir, $pagina, $items_por_pagina)
    {
        return db_filter($this->conn, $table, $orden_campo, $orden_dir, $pagina, $items_por_pagina);
    }


    /**
     * insert
     *
     * @param  mixed $table
     * @param  mixed $dto
     * @return void
     */
    public function insert($table, $dto)
    {
        return db_insert($this->conn, $table, $dto);
    }

    /**
     * update
     *
     * @param  mixed $table
     * @param  mixed $dto
     * @return void
     */
    public function update($table, $dto)
    {
        return db_update($this->conn, $table, $dto);
    }

    /**
     * delete_by_id
     *
     * @param  mixed $table
     * @param  mixed $id
     * @return void
     */
    public function delete_by_id($table, $id)
    {
        return db_delete_by_id($this->conn, $table, $id);
    }
}


/**
 * db_open
 *
 * @param  mixed $conf Array con variables de conexión
 * @return PDO
 */
function db_open($conf = null): ?PDO
{
    if (isset($conf)) {
        extract($conf);
    } else {
        $db_type = DB_TYPE;
        $sqlite_path = DB_SQLITE_PATH;
        $db_host = DB_HOST;
        $db_port = DB_PORT;
        $db_user = DB_USER;
        $db_pass = DB_PASS;
        $db_name = DB_NAME;
    }

    try {
        if ($db_type === 'sqlite') {
            $conn = new PDO("sqlite:" . $sqlite_path);
        } else {
            // Construimos la cadena de conexión (DSN) usando constantes definidas en utils.php
            $uri = $db_type . ":host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name;
            $conn = new PDO($uri, $db_user, $db_pass);
        }

        // Configuramos el modo de errores: excepciones
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Configuramos el modo de obtención de resultados: como array asociativo
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        // Registramos el error (función logging definida en utils.php)
        logging($e);
        return null;
    }
}

/**
 * Inicia una transacción en la base de datos.
 *
 * @param PDO $conn Conexión activa a la base de datos
 */
function db_begin(PDO $conn): void
{
    $conn->beginTransaction();
}

/**
 * Confirma los cambios realizados durante una transacción.
 *
 * @param PDO $conn Conexión activa a la base de datos
 */
function db_commit(PDO $conn): void
{
    if ($conn->inTransaction()) {
        $conn->commit();
    }
}

/**
 * Revierte los cambios realizados durante una transacción.
 *
 * @param PDO $conn Conexión activa a la base de datos
 */
function db_rollback(PDO $conn): void
{
    if ($conn->inTransaction()) {
        $conn->rollback();
    }
}

/**
 * Ejecuta una consulta SQL preparada y devuelve los resultados.
 *
 * @param PDO $conn Conexión activa a la base de datos
 * @param string $query Consulta SQL a ejecutar
 * @param array|null $params Parámetros para la consulta preparada
 * @return array|false Resultado como array asociativo o false en caso de error
 */
function db_query(PDO $conn, string $query, ?array $params = null): array|false
{
    try {
        // Preparamos la consulta
        $stmt = $conn->prepare($query);

        // Ejecutamos la consulta con los parámetros dados
        if ($stmt->execute($params)) {
            // Devolvemos todos los resultados
            return $stmt->fetchAll();
        }
        return false;
    } catch (PDOException $e) {
        // Registramos cualquier error ocurrido
        if (DEBUG) {
            echo $e;
        }
        logging($e);
        return false;
    }
}

/**
 * db_select
 *
 * Permite realizar SELECT con paginación. Devuelve el número total de registros y los elementos correspondientes
 * a la página que se indique.
 * @param  mixed $conn
 * @param  mixed $select
 * @param  mixed $from
 * @param  mixed $where
 * @param  mixed $params
 * @param  mixed $paginacion
 * @return array|false
 */
function db_select(PDO $conn, $select, $from, $where=null, $orden=null, $params=null, $paginacion = null): array|false
{
    try {
        // Sentencia para obtener los totales
        $sql = "SELECT count(*) as total FROM $from";
        if (isset($where) and $where != '') {
            $sql .= " WHERE $where";
        }

        $stmt = $conn->prepare($sql);

        // Ejecutamos la consulta con los parámetros dados
        if ($stmt->execute($params)) {
            // Devolvemos todos los resultados
            $res = $stmt->fetchAll();
        }
        
        $total = $res[0]['total'];
        
        // Sentencia para obtener los registros
        $sql = "SELECT $select FROM $from";
        if (isset($where) and $where != '') {
            $sql .= " WHERE $where";
        }
        if ($orden and isset($orden['orden_por'])) {
            $sql .= " ORDER BY ".$orden['orden_por'];
            if(isset($orden['orden_dir'])){
                $sql .= ' '.$orden['orden_dir'];
            }
        }

        if ($paginacion) {
            $sql .= " LIMIT " . ($paginacion['pagina'] - 1) * ($paginacion['num_items']) . ',' . $paginacion['num_items'];
        }
        //echo $sql;exit;
        $stmt = $conn->prepare($sql);

        // Ejecutamos la consulta con los parámetros dados
        if ($stmt->execute($params)) {
            // Devolvemos todos los resultados
            $res=['total' => $total, 'datos' => $stmt->fetchAll()];
            return $res;
        }
        return false;
    } catch (PDOException $e) {
        // Registramos cualquier error ocurrido
        if (DEBUG) {
            echo $e;
        }

        logging($e);
        return false;
    }
}

/**
 * Obtiene un registro por su ID.
 *
 * @param PDO $conn Conexión activa a la base de datos
 * @param string $table Nombre de la tabla
 * @param mixed $id Valor del ID a buscar
 * @param string $id_name Nombre del campo ID (por defecto 'id')
 * @return mixed Registro encontrado o false si no se encuentra
 */
function db_get_by_id(PDO $conn, string $table, mixed $id, string $id_name = 'id'): mixed
{
    // Validamos que los nombres de tabla y columna sean seguros
    if (!is_valid_identifier($table) || !is_valid_identifier($id_name)) {
        return false;
    }

    // Consulta SQL protegida con marcador de posición
    $sql = "SELECT * FROM `$table` WHERE `$id_name` = ?";

    // Ejecutamos la consulta con el parámetro
    $res = db_query($conn, $sql, [$id]);

    // Si hay resultado, devolvemos el primero, sino false
    return is_array($res) && !empty($res) ? $res[0] : false;
}

/**
 * db_filter
 *
 * @param  mixed $db
 * @param  mixed $tabla
 * @param  mixed $filtro
 * @param  mixed $orden_campo
 * @param  mixed $orden_dir
 * @param  mixed $pagina
 * @param  mixed $items_por_pagina
 * @return array
 */
function db_filter($db, $tabla, $campos, $filtro, $tipo_filtro = 'or', $orden_campo = 'id', $orden_dir = 'asc', $pagina = 0, $items_por_pagina = 20): array|false
{
    if ($db) {
        $whereArray = [];
        $params = [];
        $where = '';
        if ($filtro and count($filtro) > 0) {
            foreach ($filtro as $f) {
                $campo = $f['campo'];
                $tipo = $f['tipo'];
                if ($tipo == 'texto') {
                    if (trim($f['valor']) != '') {
                        $whereArray[] = "LOWER($campo) LIKE LOWER(?)";
                        $params[] = '%' . $f['valor'] . '%';
                    }
                } else if ($tipo == 'entero') {
                    if (is_numeric($f['valor'])) {
                        $whereArray[] = "$campo=?";
                        $params[] = $f['valor'];
                    }
                } else if ($tipo == 'id') {
                    if (is_numeric($f['valor'])) {
                        $whereArray[] = "$campo=?";
                        $params[] = $f['valor'];
                    }
                } else if ($tipo == 'intervalo') {
                    if (isset($f['min'])) {
                        $whereArray[] = "$campo>?";
                        $params[] = $f['min'];
                    }
                    if (isset($f['max'])) {
                        $whereArray[] = "$campo<?";
                        $params[] = $f['max'];
                    }
                    if (isset($f['mine'])) {
                        $whereArray[] = "$campo>=?";
                        $params[] = $f['mine'];
                    }
                    if (isset($f['maxe'])) {
                        $whereArray[] = "$campo<=?";
                        $params[] = $f['maxe'];
                    }
                }
            }
            if (count($params) > 0) {
                $where = 'WHERE ' . implode(' ' . $tipo_filtro . ' ', $whereArray);
            }
        }

        $sql_total = "SELECT COUNT(*) as total FROM $tabla $where";
        //print_r($params);
        //echo $sql_total . PHP_EOL;exit;
        $total = db_query($db, $sql_total, $params)[0]['total'];

        //echo "total: ".$total.PHP_EOL;exit;

        $num_paginas = ceil($total / $items_por_pagina);
        //echo "Pagina: $pagina";exit;

        if ($pagina < 0 or ($num_paginas > 0 and $pagina > $num_paginas)) {
            return false;
        }

        $limit = $items_por_pagina;
        $offset = $items_por_pagina * ($pagina - 1);

        if (!in_array($orden_dir, ['asc', 'desc'])) {
            return false;
        }

        if (isset($campos) and !empty($campos)) {
            $camposSql = [];
            foreach ($campos as $campo => $alias) {
                if ($alias == null) {
                    $camposSql[] = $campo;
                } else {
                    $camposSql[] = "$campo as $alias";
                }
            }
            $columnas = implode(', ', $camposSql);
        } else {
            $columnas = '*';
        }

        $sql = "SELECT $columnas FROM $tabla $where ORDER BY $orden_campo $orden_dir";

        if ($pagina > 0) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        //  echo $sql . PHP_EOL; exit;
        //      print_r($params);exit;
        $registros = db_query($db, $sql, $params);
        //      print_r($registros);exit;
        //return false;
        return ['total' => $total, 'datos' => $registros];
    } else {
        return false;
    }
}


/**
 * Inserta un nuevo registro en la tabla especificada.
 *
 * @param PDO $conn Conexión activa a la base de datos
 * @param string $table Nombre de la tabla
 * @param array $dto Datos a insertar (clave => valor)
 * @return string|int|false ID del nuevo registro o false si falla
 */
function db_insert(PDO $conn, string $table, array $dto): string|int|false
{
    // Verificamos que los datos y el nombre de la tabla sean válidos
    if (empty($dto) || !is_valid_identifier($table)) {
        return false;
    }
    
    if(isset($dto['id'])){
      unset($dto['id']);
    }

    try {
        // Extraemos las claves del array (campos de la tabla)
        $fields = array_keys($dto);
        // Escapamos los campos y validamos cada uno
        foreach ($fields as &$field) {
            if (!is_valid_identifier($field)) return false;
            $field = "`$field`";
        }

        // Marcadores de posición (?, ?, ...)
        $params = implode(', ', array_fill(0, count($dto), '?'));

        // Armamos la consulta SQL
        $fields_str = implode(', ', $fields);
        $sql = "INSERT INTO `$table` ($fields_str) VALUES ($params)";
        // Preparamos y ejecutamos la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array_values($dto))) {
            // Retornamos el último ID generado
            $id = $conn->lastInsertId();
            return $id;
        }
        return false;
    } catch (PDOException $e) {
        if(DEBUG){
          echo $e;
          exit;
        }
        logging($e);
        return false;
    }
}

/**
 * Actualiza un registro existente en la tabla.
 *
 * @param PDO $conn Conexión activa a la base de datos
 * @param string $table Nombre de la tabla
 * @param array $dto Datos actualizados (clave => valor)
 * @param string $id_name Nombre del campo ID (por defecto 'id')
 * @return bool true si se actualizó correctamente, false en caso contrario
 */
function db_update(PDO $conn, string $table, array $dto, string $id_name = 'id'): bool
{
    // Validamos datos y nombres de identificadores
    if (empty($dto) || !is_valid_identifier($table) || !is_valid_identifier($id_name)) {
        return false;
    }

    // Verificamos que exista el ID en los datos
    if (!isset($dto[$id_name])) {
        return false;
    }

    // Guardamos el ID y lo quitamos de los datos a actualizar
    $id = $dto[$id_name];
    unset($dto[$id_name]);

    // Preparamos los campos y valores para la consulta
    $fields = [];
    $values = [];

    foreach ($dto as $key => $value) {
        if (!is_valid_identifier($key)) return false;
        $fields[] = "`$key` = ?";
        $values[] = $value;
    }

    // Añadimos el ID al final de los valores
    $values[] = $id;

    // Armamos la consulta SQL
    $sql = "UPDATE `$table` SET " . implode(', ', $fields) . " WHERE `$id_name` = ?";

    try {
        // Preparamos y ejecutamos la consulta
        $stmt = $conn->prepare($sql);
        return $stmt->execute($values);
    } catch (PDOException $e) {
        logging($e);
        return false;
    }
}

/**
 * Elimina un registro por su ID.
 *
 * @param PDO $conn Conexión activa a la base de datos
 * @param string $table Nombre de la tabla
 * @param mixed $id Valor del ID a eliminar
 * @param string $id_name Nombre del campo ID (por defecto 'id')
 * @return bool true si se eliminó correctamente, false en caso contrario
 */
function db_delete_by_id(PDO $conn, string $table, mixed $id, string $id_name = 'id'): bool
{
    // Validamos los nombres de tabla y campo
    if (!is_valid_identifier($table) || !is_valid_identifier($id_name)) {
        return false;
    }

    try {
        // Preparamos la consulta con marcador de posición
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE `$id_name` = ?");
        // Ejecutamos con el parámetro
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        if(DEBUG){
          echo $e;
          exit;
        }
        return false;
    }
}

/**
 * Cierra la conexión con la base de datos.
 *
 * @param PDO|null $conn Referencia a la conexión
 */
function db_close(&$conn): void
{
    $conn = null;
}

// -----------------------------
// Funciones auxiliares privadas
// -----------------------------

/**
 * Valida que un identificador (nombre de tabla o columna) sea seguro.
 *
 * @param string $identifier Nombre a validar
 * @return bool true si es válido, false en caso contrario
 */
function is_valid_identifier(string $identifier): bool
{
    return preg_match('/^[a-zA-Z0-9_]+$/', $identifier);
}

/**
 * Registra errores en el log del servidor.
 *
 * @param Exception $e Excepción lanzada
 */
function logging(Exception $e): void
{
    error_log($e->getMessage());
}
