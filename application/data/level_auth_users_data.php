<?php
class Level_Auth_Users_Data extends Data {
    /**
     * Sample user data
     * You can also get the user data from database
     *
     * @var array
     */
    private $_users = array(
        array(
            'id'=>1,
            'fullname'=>'Zhou (Joe) Yuan',
            'username'=>'zhouy',
            'hash_pass'=>'$P$BN7rmT7I1KKipgOKsefSTOUL6QtIBE1',
            'auth_level'=>'admin'
        ),
        array(
            'id'=>2,
            'fullname'=>'Fran Mossberg',
            'username'=>'franm',
            'hash_pass'=>'$P$BC51a5TOFCeTZkh7oD/tDVAgxhYquI/',
            'auth_level'=>'user'
        ),
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

/* End of file: ./application/data/level_auth_users_data.php */
