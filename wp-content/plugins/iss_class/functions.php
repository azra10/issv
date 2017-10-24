<?php

class ISS_Class
{
    public $ClassID;
    public $GradingPeriodID;
    public $GradingPeriod;
    public $RegistrationYear;
    public $Name;
    public $ISSGrade;
    public $Subject;
    public $Status;
    public $created;
    public $updated;

    public static function Errors($errors)
    {
        if (null == $errors) {$errors =  array(); }
        if (!isset($errors['RegistrationYear'])) {$errors['RegistrationYear'] = '';}
        if (!isset($errors['GradingPeriod'])) {$errors['GradingPeriod'] = '';}
        if (!isset($errors['Name'])) {$errors['Name'] = '';}
        if (!isset($errors['ISSGrade'])) {$errors['ISSGrade'] = '';}
        if (!isset($errors['Subject'])) {$errors['Subject'] = '';}
        if (!isset($errors['Status'])) {$errors['Status'] = '';}
        return $errors;
    }
    public static function GetTableFields()
    {
        return array ( "ClassID", "GradingPeriodID", "Name",  "ISSGrade",  "Subject", "Status" );
    }
    public static function GetTableName()
    {
        return iss_get_table_name("class");
    }
    public static function GetViewName()
    {
        return iss_get_table_name("classes");
    }
    public static function GetClassList() {
        $issclasslist = array (
                    'KG' => 'Kindergarten',
                    '1' => 'Grade 1',
                    '2' => 'Grade 2',
                    '3' => 'Grade 3',
                    '4' => 'Grade 4',
                    '5' => 'Grade 5',
                    '6' => 'Grade 6',
                    '7' => 'Grade 7',
                    '8' => 'Grade 8',
                    'YB' => 'Youth Boys',
                    'YG' => 'Youth Girls',
                    'XX' => 'Unknown' 
            );
            return $issclasslist;
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
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['GradingPeriodID'])) {
                $this->GradingPeriodID = $row['GradingPeriodID'];
            }
            if (isset($row['RegistrationYear'])) {
                $this->RegistrationYear = $row['RegistrationYear'];
            }
            if (isset($row['GradingPeriod'])) {
                $this->GradingPeriod = $row['GradingPeriod'];
            }
            if (isset($row['Name'])) {
                $this->Name = $row['Name'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['Status'])) {
                $this->Status = $row['Status'];
            }
            if (isset($row['Subject'])) {
                $this->Subject = $row['Subject'];
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

class ISS_ClassService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_ClassService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_ClassService::" . print_r($message, true));
    }
    /**
     * GetClasss function
     *
     * @return array of ISS_Class Objects
     */
    public static function GetClasses()
    {
        self::debug("GetClasses");
        $regyear = iss_registration_period();
        if (!empty($regyear))   
        {     
            $list = array();
            
            global $wpdb;            
            $table =  ISS_Class::GetViewName();
            $query = "SELECT *  FROM {$table} WHERE RegistrationYear = '{$regyear}' order by  Status, Name";
            $result_set = $wpdb->get_results ( $query, ARRAY_A );
            foreach ($result_set as $obj) {
                try {
                    $list[] =  ISS_Class::Create($obj);
                } catch (Throwable $ex) {
                    self::error($ex->getMessage());
                }
            }
        }
        return $list;
    }
    public static function LoadByID($id)
    {
        try {
            self::debug("LoadByID {$id}");
            global $wpdb;
            $table =  ISS_Class::GetViewName();
            $query = "SELECT *  FROM {$table} where ClassID = {$id}";
            $row = $wpdb->get_row ( $query, ARRAY_A );
            if (null != $row)  {
                return ISS_Class::Create( $row );
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
            $result = $wpdb->delete( ISS_Class::GetTableName(), array( 'ClassID' => $id ), array( "%d" ) );
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
        $required_fields = array('Name', 'RegistrationYear', 'GradingPeriod', 'ISSGrade', 'Subject', 'Status');
        
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
                self::error ( "Add Cannot insert class." );
                return 0;
            }

            $gradingperiod =  ISS_GradingPeriodService::AddWithDefaultDate($row['RegistrationYear'], $row['GradingPeriod']);
            $row['GradingPeriodID'] = $gradingperiod->GradingPeriodID;
            
            $dsarray = array ();
            $typearray = array ();
            foreach (ISS_Class::GetTableFields () as $field) {
                if (isset ( $row [$field] )) {
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                }
            }
            
            $dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
            $typearray [] = iss_field_type ( 'created' );
          
            self::debug ( $dsarray );

            $table =  ISS_Class::GetTableName();
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
                self::error ( "Update Cannot update class." );
                return 0;
            }
            
            $id = $row['ClassID'];
            $table =  ISS_Class::GetTableName();
            $query = "SELECT *  FROM {$table} where ClassID = {$id}";
            global $wpdb;
            $orig = $wpdb->get_row ( $query, ARRAY_A );
            if (null == $orig) {
                self::error ( "Update Original class not found {$id}." );
                return 0;
            }

            // populate garding period id from registration year and grading period;
            $gradingperiod =  ISS_GradingPeriodService::AddWithDefaultDate($row['RegistrationYear'], $row['GradingPeriod']);
            $row['GradingPeriodID'] = $gradingperiod->GradingPeriodID;
           
            $update = false;
            $dsarray = array ();
            $typearray = array ();
            $result = 0;
            foreach (ISS_Class::GetTableFields () as $field) {
                if (isset($row [$field] ) && (strcmp ($orig[$field], $row [$field] ) != 0)) {
                    $update = true;
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                }
            }
            
            if ($update) {
                self::debug ( "class table update" );
                iss_write_log ( $dsarray );
                
                $result = $wpdb->update ( $table, $dsarray, array (
                        'ClassID' => $id
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
