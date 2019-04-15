<?php

class User {
	private $db;

	public function __construct() {
		$this->db = new Database();
	}

	public function checkToken($token) {
		$this->db->query('SELECT * FROM users WHERE token = ? AND TIME_TO_SEC(TIMEDIFF(tokenExpiration, NOW())) > 0');
		return $this->db->single([$token]);
	}

	public function setToken($data) {
		$this->db->query('UPDATE users SET token = :token, tokenExpiration = :tokenExpiration WHERE id = :id');
		return $this->db->execute($data);
	}

	public function login($username, $password) {
		$this->db->query('SELECT * FROM users WHERE username = ? AND verified = 1');
		$row = $this->db->single([$username]);
		return password_verify($password, $row->password) ? $row : false;
	}

	public function getAllUsers() {
		$this->db->query('SELECT * FROM users');
		return $this->db->resultSet([]);
	}

	public function getUser($id) {
		$this->db->query('SELECT * FROM users WHERE id = ?');
		$row = $this->db->single([$id]);
		return $row ? [$row] : [];
	}

	public function addUser($data) {
		$this->db->query('INSERT INTO users
							(first_name, last_name, username, email, password, vkey)
						VALUES
							(:first_name, :last_name, :username, :email, :password, :vkey)');
		return $this->db->execute($data);
	}

	public function updateUser($data) {
		$this->db->query('UPDATE users SET
							first_name = :first_name,
							last_name = :last_name,
							username = :username,
							email = :email,
							gender = :gender,
							looking = :looking,
							birthdate = :birthdate,
							biography = :biography,
							tags = :tags,
							`address` = :address,
							city = :city,
							country = :country,
							postal_code = :postal_code,
							phone = :phone
						WHERE id = :id');
		return $this->db->execute($data);
	}

	public function verifyUser($vkey) {
		$this->db->query('UPDATE users SET verified = 1 WHERE vkey = ? AND verified = 0');
		return $this->db->execute([$vkey]);
	}

	public function deleteUser($id) {
		$this->db->query('DELETE FROM users WHERE id = ?');
		return $this->db->execute([$id]);
	}

	public function isUser($id) {
		$this->db->query('SELECT * FROM users WHERE id = ?');
		$this->db->execute([$id]);
		return $this->db->rowCount() !== 0;
	}

	public function isVerified($vkey) {
		$this->db->query('SELECT verified FROM users WHERE vkey = ?');
		return $this->db->single([$vkey]);
	}

	public function getUserImages($id) {
		$this->db->query('SELECT * FROM images WHERE user_id = ?');
		return $this->db->resultSet([$id]);
	}

	public function unsetProfile($id) {
		$this->db->query('UPDATE images SET profile = 0 WHERE user_id = ?');
		return $this->db->execute([$id]);
	}

	public function addImage($data) {
		$this->db->query('INSERT INTO images
							(`user_id`, `name`, `profile`)
						VALUES
							(:user_id, :name, :profile)');
		return $this->db->execute($data);
	}

	public function uploadImage($file, $dir, $user) {
		$name = $file['name'];
		$tmpName = $file['tmp_name'];
		$size = $file['size'];
		$err = $file['err'];
		$type = $file['type'];
		$ext = strtolower(end(explode('.', $name)));
		if (in_array($ext, ['jpg', 'jpeg', 'png']) && !$err && $size < 5000000) {
			if (!file_exists($dir))
				exec('mkdir -p '.$dir);
			$dest = $user.'-'.uniqid('', true).'.'.$ext;
			move_uploaded_file($tmpName, $dir.'/'.$dest);
			return $dest;
		} else
		return false;
	}
	
	function saveImage64($data, $dir, $user){
		list($type, $data) = explode(';', $data);
		list(,$ext) = explode('/', $type);
		list(,$data) = explode(',', $data);
		if (!file_exists($dir))
			exec('mkdir -p '.$dir);
		$dest = $user.'-'.uniqid('', true).'.'.$ext;
		file_put_contents($dir.'/'.$dest, base64_decode($data));
		return $dest;
	}

}