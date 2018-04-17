<?php

class ISS_User 
{
    public $WPUserID;
    public $TeacherID;
    public $StudentID;
    public $ParentID;
    public $Email;
    public $Username;
    public $Name;
    
    public static function GetUserTableName()
    {
        return iss_get_table_name("user_mapping");
    }
    public static function GetMappingTableName()
    {
        return iss_get_table_name("wpuser");
    }
  public static function Errors()
    {
        $errors =  array();
        $errors['Name'] = '';
        $errors['Email'] = '';
        $errors['WPUserID'] = '';
        $errors['TeacherID'] = '';
        $errors['ParentID'] = '';
        $errors['StudentID'] = '';
        $errors['Username'] = '';
        return $errors;
    }
    public static function GetTableFields()
    {
        return array ("WPUserID", "TeacherID", "ParentID", "StudentID");
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
            if (isset($row['WPUserID'])) {
                $this->WPUserID = $row['WPUserID'];
            }
            if (isset($row['Name'])) {
                $this->Name = $row['Name'];
            }
            if (isset($row['Email'])) {
                $this->Email = $row['Email'];
            }
            if (isset($row['StudentID'])) {
                $this->StudentID = $row['StudentID'];
            }
            if (isset($row['ParentID'])) {
                $this->ParentID = $row['ParentID'];
            }
            if (isset($row['TeacherID'])) {
                $this->TeacherID = $row['TeacherID'];
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

class ISS_UserService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_UserService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_UserService::" . print_r($message, true));
    }
    public static function GetWPUserId($Email) {
        $userid = username_exists( $Email );
        if( null !=  $userid) {
            return $userid;
        }
        return null;
    }
    public static function DeleteUser($WPUSerID) {
        self::debug("DeleteUser {$WPUSerID}"); 
        
           if ( wp_delete_user($WPUSerID)) {
               return 1;
           }
           return 0;
    }
    public static function CreateUser($Email, $Type) {
        self::debug("CreateUser {$Type} {$Email}"); 
        if( null == username_exists( $Email ) ) {
            
                $password = wp_generate_password( 12, true );
                $user_id = wp_create_user ( $Email, $password, $Email );
                if( is_wp_error( $user_id ) )  
                {
                    self::debug( $user_id );
                    return $user_id;
                }
                else
                {
                    self::debug("User created :" .  $user_id);
                    $user = new WP_User( $user_id );
                    if (strcmp($Type, 'Teacher') == 0) { 
                        self::debug("Teacher role added");
                        $user->set_role( 'issteacherrole' );
                    }
                    elseif (strcmp($Type, 'Student') == 0) { 
                        self::debug("Student role added");
                        $user->set_role( 'issstudentrole' );
                    }
                    elseif (strcmp($Type, 'Parent') == 0) { 
                        self::debug("Parent role added");
                        $user->set_role( 'issparentrole' );
                    }
                    //wp_mail( $Email, 'Welcome to ISSV Grading Site!', 'Your password is: ' . $password );
                    wp_new_user_notification ( $user_id, null,'both' );
                    return $user_id;
                }
        } else{
            $error = new WP_Error("user", "User already exits, change the email.");
            return $error;
        }
     }
    // public static function LoadTeacherByID($id) {
    //     try {
    //         self::debug("LoadTeacherByID {$id}");
    //         global $wpdb;
    //         $table1 =  ISS_User::GetUserTableName();
    //         $table2 =  ISS_User::GetMappingTableName();
    //         $query = "SELECT WPUserID, TeacherID, ParentID, StudentID,  user_login AS Username, user_email AS Email,user_nicename AS Name
    //                     FROM {$table1}, {table2 WHERE ID = WPUserID AND TeacherID = {$id}";
    //         $row = $wpdb->get_row ( $query, ARRAY_A );
            
    //         if (null != $row) {
    //             return ISS_User::Create( $row );
    //         }
    //     } catch (Throwable $ex) {
    //         self::error($ex->getMessage());
    //     }       
    //     return null;
    // }
    // public static function Add(array $row)
    // {
    //     try {
    //         self::debug("Add"); self::debug($row);
    //         $errors = array();
    //         if (!self::isValid($row, $errors)) {
    //             self::error ( "Add Cannot insert user mapping." );
    //             return 0;
    //         }

    //         $dsarray = array ();
    //         $typearray = array ();
    //         foreach (ISS_User::GetTableFields () as $field) {
    //             if (isset ( $row [$field] )) {
    //                 $dsarray [$field] = $row [$field];
    //                 $typearray [] = iss_field_type ( $field );
    //             }
    //         }
    //         $dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
    //         $typearray [] = iss_field_type ( 'created' );
            
    //         self::debug ( $dsarray );

    //         $table =  ISS_User::GetUserTableName();
    //         global $wpdb;
    //         $result = $wpdb->insert ( $table, $dsarray, $typearray );
    //         if ($result == 1) {
    //             return 1;
    //         }
    //     } catch (Throwable $ex) {
    //         self::error($ex->getMessage());
    //     }
    //     return 0;
    // }
}
?>