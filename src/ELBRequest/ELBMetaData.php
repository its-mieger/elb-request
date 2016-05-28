<?
	namespace ELBRequest;

	/**
	 * Gets ELB meta data about the requests. Forwarded protocol, remote ip and port
	 * @package ELBRequest
	 */
	class ELBMetaData {

		const FORWARDED_PROTOCOL_HTTP = 'http';
		const FORWARDED_PROTOCOL_HTTPS = 'https';

		protected static function getElbHeader($headerName) {

			// prepend prefix
			$headerName = 'X-Forwarded-' . $headerName;

			// get Server variable key
			$key = 'HTTP_' . strtoupper(str_replace('-', '_', $headerName));

			// check existence
			if (!array_key_exists($key, $_SERVER))
				throw new ELBHeaderNotExistsException($headerName);

			return $_SERVER[$key];
		}

		/**
		 * Gets the port the client used to connect to the ELB
		 * @return int The port (e.g. 80 or 443)
		 * @throws ELBHeaderNotExistsException
		 */
		public static function getForwardedPort() {
			return self::getElbHeader('Port');
		}

		/**
		 * Gets the protocol the client used to connect to the ELB
		 * @return string The protocol (e.g. 'https' or 'http')
		 * @throws ELBHeaderNotExistsException
		 */
		public static function getForwardedProtocol() {
			return self::getElbHeader('Proto');
		}

		/**
		 * Gets the IPs of all proxies and the client within the forwarding chain.
		 * @return string[] The ips of all proxies and the client. The last address is the address of the most recent proxy which connected to the ELB.
		 * @throws ELBHeaderNotExistsException
		 */
		public static function getForwardedClientIPs() {
			return explode(',', str_replace(' ', '', self::getElbHeader('For')));
		}

		/**
		 * Checks if the client connected via HTTPS to the ELB
		 * @return bool True if connected via HTTPS. Else false
		 * @throws ELBHeaderNotExistsException
		 */
		public static function isHttps() {
			return self::getForwardedProtocol() == self::FORWARDED_PROTOCOL_HTTPS;
		}

		/**
		 * Checks if the client connected via HTTP to the ELB
		 * @return bool True if connected via HTTP. Else false
		 * @throws ELBHeaderNotExistsException
		 */
		public static function isHttp() {
			return self::getForwardedProtocol() == self::FORWARDED_PROTOCOL_HTTP;
		}

		/**
		 * Gets the IP of the the most recent proxy which connected to the ELB. This equals the remote address.
		 * @return string The remote IP. (eg. '203.0.113.7' or '2001:DB8::21f:5bff:febf:ce22:8a2e')
		 */
		public static function getRemoteIp() {
			return array_pop(self::getForwardedClientIPs());
		}

	}