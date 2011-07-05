<?php
class Users_Data extends Data {
    /**
     * Sample user data
     * You can also get the user data from database
     *
     * @var array
     */
    private $_users = array(
        array('id'=>1,'fullname'=>'Zhou (Joe) Yuan','username'=>'zhou','hash_pass'=>'$P$BN7rmT7I1KKipgOKsefSTOUL6QtIBE1'),
        array('id'=>2,'fullname'=>'Chris Lee','username'=>'chrish','hash_pass'=>'$P$BmIS1DqDSA8qAnRdCnpxkfreuxRpRY0')
    );
	

    /**
     * Get user's info based on username provided
     *
     * @return mixed NULL if no match or array if found
     */
    public function user_exists($username) { 
        $user = NULL;
        $cnt = count($this->_users);
        for ($i = 0; $i < $cnt; $i++) {
            if ($this->_users[$i]['username'] === $username) {
                $user = $this->_users[$i];
                break;
            }
        }
        return $user;
    }

}

/* End of file: ./application/data/users_data.php */
