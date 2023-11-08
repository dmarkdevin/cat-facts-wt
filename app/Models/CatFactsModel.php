<?php
namespace App\Models;
use CodeIgniter\Model;

class CatFactsModel extends Model
{
    protected $facts = "facts";
    protected $mandatoryFields;
    protected $db;

    public function __construct() {
        // Establishes a database connection and initializes mandatory fields
        $this->db = \Config\Database::connect();
        $this->mandatoryFields = ['_id','updatedAt','createdAt','type', 'text'];
        // ,'status','text','updatedAt','deleted','sentCount'
    }
    /**
     * Fetches all data from the 'facts' table
     * @return array Fetched data
     */
    public function fetchAll() {
        try {
            $builder = $this->db->table($this->facts)
                ->select('*');
            $query = $builder->get();
            return $query->getResult();
        } catch (\Exception $e) {
            // Log the error and return a generic message for production
            error_log('Error in fetchAll method: ' . $e->getMessage());
            return 'An error occurred while fetching data.';
        }
    }

    /**
     * Counts all records in the 'facts' table
     * @return int Number of records
     */
    public function countAll() {
        try {
            $builder = $this->db->table($this->facts);
            return $builder->countAllResults();
        } catch (\Exception $e) {
            error_log('Error in countAll method: ' . $e->getMessage());
            return 'An error occurred while counting records.';
        }
    }

    /**
     * Fetches a single record based on the _id
     * @param string $_id The ID to fetch
     * @return mixed Fetched record or error message
     */
    public function fetchData($_id) {
        try {
            $builder = $this->db->table($this->facts)
                ->select($this->mandatoryFields)
                ->where("_id", $_id);
            $query = $builder->get();
            return $query->getRow();
        } catch (\Exception $e) {
            error_log('Error in fetchData method: ' . $e->getMessage());
            return 'An error occurred while fetching a record.';
        }
    }

    /**
     * Creates a new record with provided data
     * @param array $data Data to be inserted
     * @return string Success message or error
     */
    public function create($data) {
        try {
            $builder = $this->db->table($this->facts);
            return $builder->insert($data);
        } catch (\Exception $e) {
            error_log('Error in create method: ' . $e->getMessage());
            return 'An error occurred while creating a record.';
        }
    }

    /**
     * Updates a single record with provided data
     * @param array $data Data to be updated
     * @return string Success message or error
     */
    public function updateOne($data) {
        try {
            $builder = $this->db->table($this->facts)
                ->where('_id', $data['_id']);
            return $builder->update($data);
        } catch (\Exception $e) {
            error_log('Error in updateOne method: ' . $e->getMessage());
            return 'An error occurred while updating a record.';
        }
    }

    /**
     * Deletes all records from the 'facts' table
     * @return string Success message or error
     */
    public function deleteAll()
    {
        try {
            return $this->db->table($this->facts)->truncate();
        } catch (\Exception $e) {
            error_log('Error in deleteAll method: ' . $e->getMessage());
            return 'An error occurred while deleting all records.';
        }
    }

    public function syncAPI($apiResponse) {
        try {

            if (empty($apiResponse)) {
                // No data to sync, exit early
                return false;
            }

            // $this->deleteAll(); // Delete all existing data before syncing

            foreach ($apiResponse as $apiEntry) {
                // Convert boolean 'deleted' value to strings 'true' or 'false'
                $apiEntry['deleted'] = ($apiEntry['deleted'] === false) ? 'false' : 'true';

                // Extract mandatory fields from the API entry
                $data = [];
                foreach ($this->mandatoryFields as $field) {
                    $data[$field] = esc($apiEntry[$field]) ?? null;
                }

                // Add '__v' field to the data
                $data['__v'] = esc($apiEntry['__v']);

                // Validate the mandatory fields
                if ($this->validateMandatoryFields($data, $this->mandatoryFields)) {

                    // Sanitize data from API before saving into the database
                    // $sanitizedData = $this->security->xss_clean($data);
                    // $sanitizedData = $this->security->html_escape($sanitizedData);

                    if ($this->fetchData( $apiEntry['_id'] )) {
                        // Update the existing data
                        $this->updateOne($data);
                    } else {
                        // Create new data entry
                        $this->create($data);
                    }

                } else {
                    // Mandatory field validation failed for this entry
                    // no action needed
                }


            }

            return true;

        } catch (\Exception $e) {
            // Log the exception
            error_log('Error in syncAPI method: ' . $e->getMessage());
            // Return an error message to indicate a problem during data synchronization
            return 'An error occurred during data synchronization.';
        }
    }

    private function validateMandatoryFields($data, $mandatoryFields)
    {
        // Check if all mandatory fields are present and not empty in the data
        foreach ($mandatoryFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false; // Return false on first invalid field
            }
        }
        return true; // All mandatory fields are valid
    }
}




