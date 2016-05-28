<?
	namespace ELBRequest;

	use Exception;

	class ELBHeaderNotExistsException extends \Exception {

		protected $header;

		public function __construct($header, $message = "", $code = 0, Exception $previous = null) {

			if (!$message)
				$message = 'Header "' . $header . '" does not exist. Request seems not to be passed through an ELB instance';

			parent::__construct($message, $code, $previous);
		}

		/**
		 * @return string
		 */
		public function getHeader() {
			return $this->header;
		}



	}