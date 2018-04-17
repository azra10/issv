<?php

class ISS_Teacher
{
    public $TeacherID;
    public $Name;
    public $Email;
    public $Status;
    public $created;
    public $updated;

    public static function Errors()
    {
        $errors =  array();
        $errors['Name'] = '';
        $errors['Email'] = '';
        $errors['Status'] = '';
        return $errors;
    }
    public static function GetTableFields()
    {
        return array ( "TeacherID", "Name",  "Email",  "Status" );
    }
    public static function GetTableName()
    {
        return iss_get_table_name("teacher");
    }

   public static function Create(array $row)
    {
        $instance = new self();
        $instance->fill( $row );
        return $instance;
    }

    protected function fill(array $row)
    {
        // fill all properties from array
        if (is_array($row) && !empty($row)) {
            if (isset($row['TeacherID'])) {
                $this->TeacherID = $row['TeacherID'];
            }
            if (isset($row['Name'])) {
                $this->Name = $row['Name'];
            }
            if (isset($row['Email'])) {
                $this->Email = $row['Email'];
            }
            if (isset($row['Status'])) {
                $this->Status = $row['Status'];
            }
            if (isset($row['created'])) {
                $this->created = $row['created'];
            }
            if (isset($row['updated'])) {
                $this->updated = $row['updated'];
            }
            return;
        }
        throw new Throwable("__construct input object is null/empty");
    }
}

class ISS_TeacherService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_TeacherService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_TeacherService::" . print_r($message, true));
    }
    /**
     * GetTeachers function
     *
     * @return array of ISS_Teacher Objects
     */
    public static function GetTeachers()
    {
        self::debug("GetTeachers");
        
        $list = array();
         
        global $wpdb;
        $table =  ISS_Teacher::GetTableName();
        $query = "SELECT *  FROM {$table} order by  Status, Name";
        $result_set = $wpdb->get_results ( $query, ARRAY_A );
        
        foreach ($result_set as $obj) {
            try {
                 $list[] =  ISS_Teacher::Create($obj);
            } catch (Throwable $ex) {
                self::error($ex->getMessage());
            }
        }
        return $list;
    }
    public static function LoadByID($id)
    {
        try {
            self::debug("LoadByID {$id}");
            global $wpdb;
            $table =  ISS_Teacher::GetTableName();
            
            $query = $wpdb->prepare("SELECT *  FROM {$table} where TeacherID = %d", $id);
            $row = $wpdb->get_row ( $query, ARRAY_A );
            if (null != $row) {
                return ISS_Teacher::Create( $row );
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }       
        return null;
    }
    public static function Load(string $email)
    {
        try {
            self::debug("Load {$email} ");
            if (!empty($email))
            {
                global $wpdb;
                $table =  ISS_Teacher::GetTableName();
                $query = $wpdb->prepare("SELECT *  FROM {$table} where Email = %s", $email);
                $row = $wpdb->get_row ( $query, ARRAY_A );
                if (null != $row) {
                    return ISS_Teacher::Create( $row );
                }
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function DeleteByID($id)
    {
        try {
            self::debug("DeleteByID {$id}");
            global $wpdb;
            $result = $wpdb->delete( ISS_Teacher::GetTableName(), array( 'TeacherID' => $id ), array( "%d" ) );
            if (1 == $result) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
    public static function isValid(array $row, array &$errors)
    {       
        $displaynames = iss_field_displaynames ();
        $required_fields = array('Name', 'Email', 'Status');
        
        foreach ($required_fields as $field) {
            if (! isset ( $row [$field] ) || empty ( $row [$field] )) {
                $errors [$field] = $displaynames [$field] . " is required.";
            } else {
                iss_field_valid($field, $row [$field], $errors, '');
            }
        }
        if (empty($errors)) {
            return true;
        }
        self::error('isValid false');
        self::error($errors);
        return false;
    }
    
    /**
    *
    *  @param  array of values
    *  @return int 1 if successfully added the record or 0 for fail
    */
    public static function Add(array $row)
    {
        try {
            self::debug("Add"); self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error ( "Add Cannot insert teacher." );
                return 0;
            }

            $dsarray = array ();
            $typearray = array ();
            foreach (ISS_Teacher::GetTableFields () as $field) {
                if (isset ( $row [$field] )) {
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                }
            }
            $dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
            $typearray [] = iss_field_type ( 'created' );
            
            self::debug ( $dsarray );

            $table =  ISS_Teacher::GetTableName();
            global $wpdb;
            $result = $wpdb->insert ( $table, $dsarray, $typearray );
            if ($result == 1) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    public static function Update(array $row)
    {
        try {
            self::debug("Update");self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error ( "Update Cannot update teacher." );
                return 0;
            }
            
            $id = $row['TeacherID'];
            $table =  ISS_Teacher::GetTableName();
            $query = "SELECT *  FROM {$table} where TeacherID = {$id}";
            global $wpdb;
            $orig = $wpdb->get_row ( $query, ARRAY_A );
            if (null == $orig) {
                self::error ( "Update Original teacher not found {$id}." );
                return 0;
            }
            $update = false;
            $dsarray = array ();
            $typearray = array ();
            $result = 0;
            foreach (ISS_Teacher::GetTableFields () as $field) {
                if (isset($row [$field] ) && (strcmp ($orig[$field], $row [$field] ) != 0)) {
                    $update = true;
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                }
            }
            if ($update) {
                self::debug ( "teacher table update" );
                self::debug ( $dsarray );
                
                $result = $wpdb->update ( $table, $dsarray, array (
                        'TeacherID' => $id
                ), $typearray, array (
                        '%d'
                ) );
                if (1 === $result) {
                    return 1;
                }
            }
        } catch (Throwable $ex) {
            self::error ( $ex->getMessage () );
        }
        return 0;
    }
}
