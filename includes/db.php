<?php
require_once __DIR__ . '/config.php';

function get_mysqli_connection(): mysqli {
	static $conn = null;
	if ($conn instanceof mysqli) {
		return $conn;
	}

	// Connect without selecting DB first to avoid exceptions for missing DB
	$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS);
	if ($conn->connect_error) {
		http_response_code(500);
		echo 'Database connection failed.';
		exit;
	}
	$conn->set_charset('utf8mb4');

	// Temporarily disable strict mysqli error reporting when probing/creating DB
	$prevReport = mysqli_report(MYSQLI_REPORT_OFF);

	$hasDb = @$conn->select_db(DB_NAME);
	if (!$hasDb) {
		@$conn->query('CREATE DATABASE IF NOT EXISTS `'.DB_NAME.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
		@$conn->select_db(DB_NAME);
		$schemaFile = dirname(__DIR__) . '/sql/schema.sql';
		if (is_file($schemaFile)) {
			$sql = file_get_contents($schemaFile);
			if ($sql !== false && $sql !== '') {
				@$conn->multi_query($sql);
				while (@$conn->more_results()) { @$conn->next_result(); }
			}
		}
	}

	// Restore previous reporting mode (use default strict after setup)
	mysqli_report($prevReport);

	return $conn;
}

function fetch_all_assoc(mysqli_stmt $stmt): array {
	$result = $stmt->get_result();
	return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
?>
