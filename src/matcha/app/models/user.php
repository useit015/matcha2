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
		$this->db->query('SELECT * FROM users, images WHERE users.id = images.user_id AND images.profile = 1');
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
							rating = :rating,
							postal_code = :postal_code,
							phone = :phone
						WHERE id = :id');
		return $this->db->execute($data);
	}

	public function updateUserPosition($data) {
		$this->db->query('UPDATE users SET
							lat = :lat,
							lng = :lng
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

	public function saveImage64($data, $dir, $user) {
		list($type, $data) = explode(';', $data);
		list(,$ext) = explode('/', $type);
		list(,$data) = explode(',', $data);
		if (!file_exists($dir))
			exec('mkdir -p '.$dir);
		$dest = $user.'-'.uniqid('', true).'.'.$ext;
		file_put_contents($dir.'/'.$dest, base64_decode($data));
		return $dest;
	}

	public function match($matcher, $matched) {
		$this->db->query('SELECT * FROM matches where matcher = ? AND matched = ?');
		if (!$this->db->resultSet([$matcher, $matched])) {
			$this->db->query('INSERT INTO matches (matcher, matched) VALUES (?, ?)');
			return $this->db->execute([$matcher, $matched]);
		}
		return [];
	}

	public function unmatch($matcher, $matched) {
		$this->db->query('DELETE FROM matches where matcher = ? AND matched = ?');
		return $this->db->execute([$matcher, $matched]);
	}

	public function getMatches($id) {
		$this->db->query('SELECT
							matches.matched as matched_id,
							matches.created_at as match_date,
							users.username as username,
							images.name as profile_image
						FROM matches
						INNER JOIN users
						ON matches.matched = users.id
						INNER JOIN images
						ON matches.matched = images.user_id
						where matches.matcher = ?
						AND images.profile = 1');
		$following = $this->db->resultSet([$id]);
		$this->db->query('SELECT
							matches.matcher as matcher_id,
							matches.created_at as match_date,
							users.username as username,
							images.name as profile_image
						FROM matches
						INNER JOIN users
						ON matches.matcher = users.id
						INNER JOIN images
						ON matches.matcher = images.user_id
						where matches.matched = ?
						AND images.profile = 1');
		$followers = $this->db->resultSet([$id]);
		return array_merge($following, $followers);
	}

	public function getBlocked($id) {
		$this->db->query('SELECT * FROM blocked where blocker = ? OR blocked = ?');
		return $this->db->resultSet([$id, $id]);
	}

	public function getHistory($id) {
		$this->db->query('SELECT
							history.visitor as visitor_id,
							history.created_at as visit_date,
							users.username as username,
							images.name as profile_image
						FROM history
						INNER JOIN users 
						ON history.visitor = users.id
						INNER JOIN images
						ON history.visitor = images.user_id
						WHERE history.visited = ?
						AND images.profile = 1');
		$visitors = $this->db->resultSet([$id]);
		$this->db->query('SELECT
							history.visited as visited_id,
							history.created_at as visit_date,
							users.username as username,
							images.name as profile_image
						FROM history
						INNER JOIN users 
						ON history.visited = users.id
						INNER JOIN images
						ON history.visited = images.user_id
						WHERE history.visitor = ?
						AND images.profile = 1');
		$visited = $this->db->resultSet([$id]);
		return array_merge($visitors, $visited);
	}

	public function block($blocker, $blocked) {
		$this->db->query('SELECT * FROM blocked where blocker = ? AND blocked = ?');
		if (!$this->db->resultSet([$blocker, $blocked])) {
			$this->db->query('INSERT INTO blocked (blocker, blocked) VALUES (?, ?)');
			return $this->db->execute([$blocker, $blocked]);
		}
		return [];
	}

	public function history($visitor, $visited) {
		$this->db->query('INSERT INTO history (visitor, visited) VALUES (?, ?)');
		return $this->db->execute([$visitor, $visited]);
	}

}
