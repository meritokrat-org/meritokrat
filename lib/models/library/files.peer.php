<?

class library_files_peer extends db_peer_postgre
{
	protected $table_name = 'files';
        protected $mimes = array('hqx'	=>	'application/mac-binhex40',
				'cpt'	=>	'application/mac-compactpro',
				'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
				'bin'	=>	'application/macbinary',
				'dms'	=>	'application/octet-stream',
				'lha'	=>	'application/octet-stream',
				'lzh'	=>	'application/octet-stream',
				'exe'	=>	array('application/octet-stream', 'application/x-ms-dos-executable'),
                                'msi' => 'application/x-msi',
				'psd'	=>	'application/x-photoshop',
				'so'	=>	'application/octet-stream',
				'sea'	=>	'application/octet-stream',
				'dll'	=>	'application/octet-stream',
				'oda'	=>	'application/oda',
				'pdf'	=>	array('application/pdf', 'application/x-download'),
				'ai'	=>	'application/postscript',
				'eps'	=>	'application/postscript',
				'ps'	=>	'application/postscript',
				'smi'	=>	'application/smil',
				'smil'	=>	'application/smil',
				'mif'	=>	'application/vnd.mif',
                                'fb2'   =>      array('application/x-fb2','text/xml','application/octet-stream'),
				'xls'	=>	array('application/excel', 'application/vnd.ms-excel'),
				'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
				'wbxml'	=>	'application/wbxml',
				'wmlc'	=>	'application/wmlc',
				'dcr'	=>	'application/x-director',
				'dir'	=>	'application/x-director',
				'dxr'	=>	'application/x-director',
				'dvi'	=>	'application/x-dvi',
				'gtar'	=>	'application/x-gtar',
				'gz'	=>	'application/x-gzip',
                                '7z'    =>  'application/x-7z-compressed',
				'swf'	=>	'application/x-shockwave-flash',
				'sit'	=>	'application/x-stuffit',
				'tar'	=>	'application/x-tar',
				'tgz'	=>	'application/x-tar',
				'xhtml'	=>	'application/xhtml+xml',
				'xht'	=>	'application/xhtml+xml',
				'zip'	=>      array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
                                'rar'   =>      array('application/x-rar-compressed','application/x-rar'),
				'mid'	=>	'audio/midi',
				'midi'	=>	'audio/midi',
				'mpga'	=>	'audio/mpeg',
				'mp2'	=>	'audio/mpeg',
				'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mp3'),
				'aif'	=>	'audio/x-aiff',
				'aiff'	=>	'audio/x-aiff',
				'aifc'	=>	'audio/x-aiff',
				'ram'	=>	'audio/x-pn-realaudio',
				'rm'	=>	'application/vnd.rn-realmedia',
				'rpm'	=>	'audio/x-pn-realaudio-plugin',
				'ra'	=>	'audio/x-realaudio',
				'rv'	=>	'video/vnd.rn-realvideo',
				'wav'	=>	'audio/x-wav',
				'bmp'	=>	'image/bmp',
				'gif'	=>	'image/gif',
				'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
				'png'	=>	array('image/png',  'image/x-png'),
				'tiff'	=>	'image/tiff',
				'tif'	=>	'image/tiff',
				'css'	=>	'text/css',
				'html'	=>	'text/html',
				'htm'	=>	'text/html',
				'shtml'	=>	'text/html',
				'txt'	=>	'text/plain',
				'text'	=>	'text/plain',
				'log'	=>	array('text/plain', 'text/x-log'),
				'rtx'	=>	'text/richtext',
				'rtf'	=>	array('text/rtf','application/rtf'),
				'xml'	=>	'text/xml',
				'xsl'	=>	'text/xml',
				'mpeg'	=>	'video/mpeg',
				'mpg'	=>	'video/mpeg',
				'mpe'	=>	'video/mpeg',
				'qt'	=>	'video/quicktime',
				'mov'	=>	'video/quicktime',
                                '3gp'	=>	'video/3gpp',
				'avi'	=>	'video/x-msvideo',
				'movie'	=>	'video/x-sgi-movie',
				'doc'	=>	array('application/msword','application/force-download'),
				'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'word'	=>	array('application/msword', 'application/octet-stream'),
                                'odt'   =>  'application/vnd.oasis.opendocument.text',
				'xl'	=>	'application/excel',
				'eml'	=>	'message/rfc822',
                                'wml' => 'text/vnd.wap.wml',
                                'tpl' => 'application/vnd.sonyericsson.mms-template',
                                'wmlc' => 'application/vnd.wap.wmlc',
                                'wmls' => 'text/vnd.wap.wmlscript',
                                'wmlsc' => 'application/vnd.wap.wmlscriptc',
                                'wbmp' => 'image/vnd.wap.wbmp',
                                'thm' => array('application/vnd.eri.thm', 'application/octet-stream'),
                                'mpn' =>  array('application/vnd.mophun.application', 'application/octet-stream'),
                                'mpc' => 'application/vnd.mophun.certificate',
                                'jad' => 'text/vnd.sun.j2me.app-descriptor',
                                'mel' => 'text/x-vmel',
                                'imy' => 'audio/imelody',
                                'mmf' => 'application/vnd.smaf',
                                'emy' => 'text/x-vmel',
                                'amr' => 'audio/amr',
                                'ogg' => 'audio/ogg',
                                'wav' => array('audio/x-wav','audio/wav'),
                                'hid' => 'application/x-tar',
                                'imy' => 'text/x-imelody',
                                'emy' => 'text/x-emelody',
                                'vcf' => 'text/x-vcard',
                                'vcs' => 'text/x-vcalendar',
                                'ics' => 'text/calendar',
                                'smil' => 'application/smil',
                                'smi' => 'application/smil',
                                'jar' => array('application/java-archive','application/x-java-archive'),
                                'sis' => 'application/vnd.symbian.install',
                                'sisx' => 'x-epoc/x-sisx-app',
                                'midi' => 'audio/midi',
                                'mid' => 'audio/midi',
                                'rmf' => 'audio/rmf',
                                'mms' => 'application/vnd.wap.mms-message',
                                'mp4' => 'video/mp4',
                                'iso' => 'application/x-cd-image'
			);

	/**
	 * @return files_peer
	 */
	public static function instance()
	{
		return parent::instance( 'library_files_peer' );
	}

	public function get_icon($ext)
	{
            $icons = array('doc' => 'MS-Word.png',
                                 'docx' => 'MS-Word.png',
                                 'DOC' => 'MS-Word.png',
                                 'odt' => 'MS-Word.png',
                                 'ODT' => 'MS-Word.png',
                                 'rtf' => 'MS-Word.png',
                                 'RTF' => 'MS-Word.png',
                                 'fb2' => 'MS-Word.png',
                                 'xls' => 'MS-Excel.png',
                                 'XLS' => 'MS-Excel.png',
                                 'csv' => 'MS-Excel.png',
                                 'CSV' => 'MS-Excel.png',
                                 'xlsx' => 'MS-Excel.png',
                                 'XLSX' => 'MS-Excel.png',
                                 'xml' => 'MS-Excel.png',
                                 'XML' => 'MS-Excel.png',
                                 'pdf' => 'pdf.png',
                                 'PDF' => 'pdf.png',
                           );
		if (in_array($ext,array_keys($icons))) return $icons[$ext];
                else return 'unknown.png';
	}

	public function get_by_group( $id, $album_id = null )
	{
		$where = array('group_id' => $id);

		if( !is_null($album_id) )
		{
			$where['dir_id'] = $album_id;
		}

		return $this->get_list( $where );
	}

	public static function generate_file_salt()
	{
		return rand(1000000, 9999999);;
	}


        public function formatBytes($bytes, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= pow(1024, $pow);

            return round($bytes, $precision) . ' ' . $units[$pow];
        }

        public function is_allowed_filetype($filename, $file_type)
       {

               $extension = strtolower(end(explode('.', $filename)));
               if (in_array($extension, array_keys($this->mimes))) {
                       if (is_array($this->mimes[$extension]))
                       {
                               if (in_array($file_type, $this->mimes[$extension]))
                               {
                                       return true;
                               }
                       }
                       else
                       {
                               if ($this->mimes[$extension] == $file_type)
                               {
                                       return true;
                               }
                       }
               }

               return false;
       }
              
	
	public function get_item( $primary_key )
	{
	
                $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :id LIMIT 1';
                $bind = array('id' => $primary_key);
                $data = db::get_row( $sql, $bind, $this->connection_name );
		return $data;
	}
}