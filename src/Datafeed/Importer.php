<?php

namespace Datafeed;

use Datafeed\DBAdapter;
use SplFileObject;

class Importer
{
	/** @var  DBAdapter */
	private $adapter;

	/** @var SplFileObject  */
	private $file;

	/**
	 * @param DBAdapter $adapter
	 */
	public function __construct(DBAdapter $adapter)
	{
		$this->adapter = $adapter;
	}

	/**
	 * @param string $fileName
	 */
	public function setFile($fileName)
	{
		if ($fileName) {
			$this->file = new SplFileObject($fileName);
			$this->file->setFlags(SplFileObject::READ_CSV);
		}
	}


	public function importToTable()
	{
		$this->adapter->truncateTable();
		$headers = $this->file->fgetcsv();

		while (!$this->file->eof()) {
			$row = $this->file->fgetcsv();
			if (count($headers) === count($row)) {
				$data = array_combine($headers, $row);
				$this->adapter->insertRow($data);
			}
		}
	}
}
