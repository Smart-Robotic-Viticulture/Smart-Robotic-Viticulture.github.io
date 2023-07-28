<?php
class MessageService
{
        private $db;

        function __construct($dbfile = "/tmp/test.db") {
		$this->db = new SQLite3($dbfile);

		$this->initDB();
	}

	function __destruct() {
		$this->db->close();
	}

	private function initDB()
	{
		$this->db->exec("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT,
					 topic NOT NULL, 
					 message NOT NULL, 
                                         uuid VARCHAR NOT NULL UNIQUE, 
                                         readtime LONG,
                                         expiry DATE DEFAULT (datetime('now', '+7 day')))");
		$this->db->exec('PRAGMA journal_mode = wal;');
		$this->db->busyTimeout(1000);

		$this->prune();
	}

	private function prune()
	{
		$this->db->exec("DELETE FROM messages WHERE expiry < datetime('now')");
	}

	public function clear()
	{
		//$this->db->exec("DELETE FROM messages");
		$this->db->exec("DROP TABLE messages");
		$this->initDB();
	}

	public function push($topic, $message) {
		$st = $this->db->prepare("INSERT INTO messages (topic, message, uuid) VALUES (:topic, :message, :uuid)");
		$st->bindValue(':topic', $topic, SQLITE3_TEXT);
		$st->bindValue(':message', $message, SQLITE3_TEXT);
		$st->bindValue(':uuid', uniqid(), SQLITE3_TEXT);
		$st->execute();
		$st->close();
	}

	public function pull($topic) {
		$this->db->exec('BEGIN;');
		$st = $this->db->prepare("SELECT * FROM messages WHERE topic = :topic AND (readtime IS NULL OR readtime < " . time() . ") ORDER BY id ASC LIMIT 1");
		$st->bindValue(':topic', $topic, SQLITE3_TEXT);
		$res = $st->execute();

		if ($r = $res->fetchArray(SQLITE3_ASSOC)) {
			$this->db->exec("UPDATE messages SET readtime = " . (time() + 300) . " WHERE id = {$r['id']}");
		}

		$this->db->exec('COMMIT;');
		$st->close();
		return $r;
	}

	public function delete($uuid) {
		//print "Deleting $uuid\n";
		$st = $this->db->prepare('DELETE FROM messages WHERE uuid = :uuid');
		$st->bindValue(':uuid', $uuid, SQLITE3_TEXT);
		$st->execute();
		$st->close();
	}

	public function count() {
		$res = $this->db->query('SELECT count(*) as count FROM messages');
		$r = $res->fetchArray(SQLITE3_NUM);
		return $r[0];
	}
};
?>
