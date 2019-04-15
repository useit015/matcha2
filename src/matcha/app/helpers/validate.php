<?php

class Validator {
	public function validatePassword($password) {
		if (empty($password)) {
			return 'Password shall not be empty';
		} else if (strlen($password) < 8 || strlen($password) > 12) {
			return 'Passwords must be between 8 and 12 characters long';
		} elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]+$/', $password)) {
			return 'Passwords must contain at least one letter, one number and one special char';
		} else {
			return '';
		}
	}

	public function validateEmail($email) {
		if (empty($email)) {
			return 'Email shall not be empty';
		// } elseif ($this->userModel->findUserByEmail($email)) {
		// 	return 'Email is already taken';
		} else {
			return '';
		}
	}

	public function validateName($name, $genre) {
		if (empty($name)) {
			return $genre.' name shall not be empty';
		} elseif (strlen($name) < 5 || strlen($name) > 10) {
			return $genre.' name must be between 6 and 10 characters long';
		} else {
			return '';
		}
	}

	public function validateConfirmPassword($password, $confPassword) {
		if (empty($confPassword)) {
			return 'Please confirm your password';
		} elseif ($password != $confPassword) {
			return 'Passwords do not match';
		} else {
			return '';
		}
	}

	public function validateExistingPassword($password) {
		if (empty($password)) {
			return 'Password shall not be empty';
		// } elseif (!$this->userModel->login($_SESSION['user_email'], $password)) {
		// 	return 'Wrong password';
		} else {
			return '';
		}
	}

	public function loginValidateEmail($email) {
		if (empty($email)) {
			return 'Email shall not be empy';
		// } elseif (!$this->userModel->findUserByEmail($email)) {
		// 	return 'No user found';
		} else {
			return '';
		}
	}

	public function loginValidatePassword($password) {
		if (empty($password)) {
			return 'Password shall not be empy';
		} else {
			return '';
		}
	}
}
