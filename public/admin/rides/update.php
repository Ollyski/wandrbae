public function update() {
    if(!isset($this->ride_id)) {
      return false;
    }
    
    // Same state validation as in create method
    if (!isset($this->state)) {
      die("Error: State is not set.");
    }

    $this->state = strtoupper(trim($this->state));

    $valid_states = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];

    // Ensure state is not empty and is a valid abbreviation
    if (empty($this->state) || !in_array(strtoupper(trim($this->state)), $valid_states)) {
      die("Error: Invalid or missing state. Please select a valid U.S. state.");
    }
    
    $attributes = $this->sanitized_attributes();
    $attribute_pairs = [];
    foreach($attributes as $key => $value) {
      if($key != 'ride_id') { // Skip the ID in SET clause
        $attribute_pairs[] = "{$key}='{$value}'";
      }
    }
    
    $sql = "UPDATE ride SET ";
    $sql .= join(', ', $attribute_pairs);
    $sql .= " WHERE ride_id='" . self::$database->escape_string($this->ride_id) . "' ";
    $sql .= "LIMIT 1";
    
    $result = self::$database->query($sql);
    return $result;
  }