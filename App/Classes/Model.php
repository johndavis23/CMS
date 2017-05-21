<?php
namespace App\Classes;
/*
 * Last Update: June 25 2016
 * 
 * This is a generic database model to extend for our various database 
 * interactions. This also helps to decouple our database implementation. 
 * 
 * I added in phpdocs so users would get nice hints. I realize this isn't "Clean" but
 * it seemed worthwhile here as this class should rarely be updated and it serves to 
 * supply the core funtionality of the data layer.
 *    
 * 
 * @author John Davis
 */
use App\Util\Util;
use App\Interfaces\CRUD;
/*
include_once('Config/config.php');

spl_autoload_register(function ($name) {
    include_once "Models/".$model.".php";
});
*/

class Model implements CRUD//, ModelInterface
{
    protected   $db;
    protected   $id_field;
    protected   $table;
    protected   $fields;
   // protected   $readable for select statements

    function __construct( $table,  $idField,  $database = "default")  
    {
        $this->id_field = $idField;
        $this->table    = $table;
        $this->db       = Database::getDatabase($database);
		if(empty($fields))//also children to manually set fields for performance
		{
			// find out the format of our table so we can match parameters  
	        $this->getTableColumns($table);
		}
    }


	/**
	 * Inserts a database entry using an array of values (ex: create(["id"=>1, "name"=>"John", "city"=>"Knoxville"])).
	 *
	 * @param $fieldValuePairs an array containing the fields and values like this [$fieldName => $value, ...]
	 * 
	 * @return void
	 * 
	 */
    public function create(array $fieldValuePairs) 
    {
        $payload       = $this->getQueryPayloads($fieldValuePairs);
        $questionArray = array_fill(0, count($payload['parameters']), "?");
		
        $query 		   = "INSERT INTO $this->table (".join(", ", $payload['columns']).")".
                         " VALUES (".join(", ", $questionArray).")";
						 
        $results       = $this->callPreparedQueryWithPayload($query, $payload);
		return $this->db->getInsertId();
    }
	
	
	/**
	 * Returns all database entries that match the where clause (ex: read(["id"=>1])). See also: readOffset
	 *
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @return Array()
	 * 
	 */
    public function read(array $where)
    {
    	if(empty($where))
			return $this->readAll();
		
        $payload = $this->getQueryPayloads($where);
        $query   = "SELECT * FROM $this->table WHERE ".join("= ? AND ", $payload['columns'] ).' = ?';
        $results = $this->callPreparedQueryWithPayload($query, $payload);
		return $results;
    }
	
	
	/**
	 * Updates database entries that match the where clause with name value pairs (ex: update(["name"=>"John","city"=>"Knoxville"],["id"=>1])).
	 * 
	 * @param $update an array containing the values and fields to set [$fieldName => $value, ...] 
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @return void
	 * 
	 */
    public function update(array $update, array $where)
    {
    	//get the relevent arrays for our prepared query
        $payload      = $this->getQueryPayloads($update);
  		$wherePayload = $this->getQueryPayloads($where);
  		
		$query        = "UPDATE $this->table SET ".join(" = ?, ", $payload['columns']).
		                " = ? WHERE ".join(" = ?, ", $wherePayload['columns'])." = ?";
						
	   	$payload['types']     .= $wherePayload['types'];
	   	$payload['parameters'] = array_merge($payload['parameters'], $wherePayload['parameters']);

		
        $results = $this->callPreparedQueryWithPayload($query, $payload);
    }
	
	
	/**
	 * Deletes all database entries satisfying the where clause (ex: delete(["id"=>1])). 
	 * 
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @param $limit (optional) if set, the max number of results to delete
	 * @return void
	 */
    public function delete(array $where, $limit = null)
    {
    	$payload = $this->getQueryPayloads($where);
		
    	if(!is_null($limit))
		{
			$limit =  " LIMIT ? "; 
			$payload['parameters'][] = $limit; 
	        $payload['types'] .= "i";
		} 
		else 
		{
			$limit = "";
		}
    	
        $query = "DELETE FROM $this->table WHERE ".join(" = ?, ", $payload['columns'])." = ? $limit";  
       
        $results = $this->callPreparedQueryWithPayload($query, $payload);
    }

	
	/**
	 * Checks if an entry exists using an array of name value pairs where each name is a field name and each value is (ex: exists(["id"=>1])). 
	 * 
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @return bool 
	 */
    public function exists(array $where)
    {
        $payload = $this->getQueryPayloads($where);
       
	  	
		$where   = 	" WHERE ".join(" = ? AND ", $payload['columns'] )." = ?"; 
        $query   = "SELECT EXISTS( SELECT 1 FROM $this->table $where )";
		
        $results = $this->callPreparedQueryWithPayload($query, $payload);
		
		$first_value = reset($results[0]);
		
		return $first_value > 0;
		
    }
	
	
	/**
	 * Counts the entries satisfying the where conditions (ex: count(["Name"=>"John"])). 
	 * 
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @return int
	 */
 	public function count(array $where)
    {
        $payload = $this->getQueryPayloads($where);
        $query   = "SELECT count(*) AS total FROM $this->table WHERE ".join("= ? AND ", $payload['columns'] ).' = ?';
            
        $results = $this->callPreparedQueryWithPayload($query, $payload);
		
        return $results[0]['total'];
    }
	
	
	/**
	 * Returns the entries using an offset and limit satisfying $where conditions 
	 * (ex: readOffset(["Name"=>"John"], $pageSize, $currentPage * $pageSize) or readOffset(["Name"=>"John", "City"=>"Knoxville"]))
	 * 
	 * @param $where an array containing the fields and values [$fieldName => $value, ...] Each entry represents " fieldName = value"
	 * @param $offset (optional) an integer representing how many records to skip
	 * @param $limit  (optional) an integer representing the max number of results to return
	 * @return int
	 */
	public function readOffset(array $where,  $limit = null, $offset = null) 
	{
		$payload = $this->getQueryPayloads($where); 
		
		//manually add the limit parameters and types for prepared query
		if(!is_null($limit) & is_int($limit))
		{
			$payload['parameters'][] = $limit; 
			$limit =  " LIMIT ? "; 
	        $payload['types'] .= "i";
		} 
		else 
		{
			$limit = "";
		}
		
		//manually add the offset parameters and types for prepared query
		if(!is_null($offset) & is_int($offset))
		{
			$payload['parameters'][] = $offset;
			$offset =  " OFFSET ? ";  
	        $payload['types'] .= "i";
		} 
		else 
		{
			$limit = "";
		}
		
		if(!empty($where))
			$where   = " WHERE ".join("= ? AND ", $payload['columns'] )." = ? ";
		
        $query   = "SELECT * FROM $this->table $where $limit $offset";
        
		$results = $this->callPreparedQueryWithPayload($query, $payload);
		
        return $results;
	}



	/**
	 * Returns the entries that have matching ids. 
	 *
	 * @return array
	 */
    public function readWithID($id)
    {
        //if its an array of ids, do an in query
        if (is_array($id))
        {
            $query   = "SELECT * FROM $this->table WHERE ".$this->id_field." IN ( ".join(", ", $id ).')';
            $results = $this->callPreparedQueryWithPayload($query, $payload);
            return $results;
        }
        return $this->read([$this->id_field => $id])[0];
    }
    
	
	/**
	 * Returns all entries. Be careful, this could take a while! 
	 *
	 * @return array
	 */
    public function readAll()
    {
        $query = "SELECT * FROM $this->table"; 
        $rows  = $this->db->query($query);
        return $rows;
    }
	
	
	/**
	 * Returns all the ids for all entries. 
	 *
	 * @return array
	 */
    public function readAllIds()
    {
        $query = "SELECT $this->id_field as id FROM $this->table"; 
        $rows  = $this->db->query($query);
        return $rows;
    }
	
	public function getIdField()
	{
		return $this->id_field;
	}
	/**************            Private functions           ***************/
	
	
    private function callPreparedQueryWithPayload($query, array $payload)
	{
		try
		{
			$bind_params = array_merge([$query, $payload['types']], $payload['parameters']); 
		    $results 	 = call_user_func_array([$this->db, 'preparedQuery'], $bind_params);
			return $results;
		}
		catch(DataException $e)
		{
			Util::error_log($e);
			return false;
		}
	}
    
	
    
    
    /**
	 * Gets and Sets the fields of the table for use in preparing a type string for prepared queries 
	 * @param $table A string that matches the name of your table
	 * 
	 * @return array Format is: [[[Field] => id, [Type] => int(7)] , ... ]
	 */
    private function getTableColumns( $table)
    {
        $r = $this->db->query("SHOW COLUMNS FROM $table", false);
		
        if (!$r) 
        {
            Util::error_log( "Could not run query to get table columns for $table.");
            return;
        }
        
        $fields = [];
        foreach($r as $column)
        {
            $fields[$column["Field"]] = $column;
        }
        
        $this->fields = $fields;
       
        return $fields;
    }
	
	
	
	/**
	 * Gets the type charcter for use in the prepared query type string
	 * @param $fieldname A string that matches the name of the field
	 * 
	 * @return char "i", "d" "s", or "b"
	 */
    private function determineFieldType( $fieldname)
    {
        $type = strtolower($fieldname);  
       
        //TODO: if size > max_allowed_packet return "b"
        //TODO: Use arrays for each type and in_array
        
        if(strpos( $type,"int") !== false)
        {
            return "i";
        }
        elseif(strpos( $type, "double")!== false || 
               strpos( $type, "float")!== false ||
               strpos( $type, "real")!== false ||
               strpos( $type, "precision")!== false)
        {
            return "d";
        }
        else//"char","date" etc...
        {
            return "s";

        }
    }
	
	
    /**
	 * Generates and Returns all needed data to generate a prepared query
	 * @param $update A name value pair array ["FieldName"=>"Value", "FieldName2","Value2", ...]
	 * 
	 * @return array ["types"=>$types, "columns"=>$queryColumns,"parameters"=>$queryParameters ]
	 */
    private function getQueryPayloads(array $update)
    {
        $types 			 = "";
        $queryParameters = [];
        $queryColumns 	 = [];
       
		//for each known valid field
        foreach($this->fields as $field)
        {
			// see if there is a matching field in update
			// is $field['Field']
			// in the keys for update?
            if(array_key_exists($field['Field'], $update))
            {
            	
                $types 			  .= $this->determineFieldType($field['Type']);
                $queryColumns[]    = $field['Field'];
                $queryParameters[] = $update[$field['Field']];
			
                unset($update[$field['Field']]); //remove good stuff from update so we know what's left (the bad stuff.)
            }
			
			//the bad stuff.
            //if any are still set, they aren't in our field list. Notify the developer. 
            if(count($update)>0) 
            {
                foreach($update as $key => $pair)
                {
                    Util::error_log("Invalid Field Supplied To Prepared Query: $key as $pair.");
                }
            }
        }
        return ["types"=>$types, "columns"=>$queryColumns,"parameters"=>$queryParameters ];
    
    }
	
	
	
    /**
	 * Determines if a field is a valid field and uses a date type
	 * 
	 * @param $fieldName A string that matches the name of the field to check
	 * 
	 * @return bool
	 */
    private function isDateField($dateField) 
    {
        if(isset($this->fields[$dateField]))//is it a valid field?
        {
            //check Type for that field for 'date' as in date datetime etc.
            return strpos(strtolower($this->fields[$dateField]['Type']), 'date')!== false;
        }
        return false;
    }
}