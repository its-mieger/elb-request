<?
	namespace ELBRequestTest;

	use ELBRequest\ELBMetaData;

	class ElbMetaDataTest extends \PHPUnit_Framework_TestCase{

		public function testGetForwardedPort() {
			$_SERVER['HTTP_X_FORWARDED_PORT'] = 443;

			$this->assertEquals(443, ELBMetaData::getForwardedPort());
		}

		public function testGetForwardedProtocol() {
			$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'http';

			$this->assertEquals('http', ELBMetaData::getForwardedProtocol());
		}

		public function testGetForwardedClientIPs() {
			$_SERVER['HTTP_X_FORWARDED_FOR'] = '210.0.0.3, 2001:DB8::21f:5bff:febf:ce22:8a2e, 127.0.0.1';

			$this->assertEquals([
				'210.0.0.3',
			    '2001:DB8::21f:5bff:febf:ce22:8a2e',
			    '127.0.0.1',
			], ELBMetaData::getForwardedClientIPs());
		}

		public function testIsHttp() {
			$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'http';

			$this->assertTrue(ELBMetaData::isHttp());
		}

		public function testIsHttps() {
			$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

			$this->assertTrue(ELBMetaData::isHttps());
		}

		public function testGetRemoteIp() {
			$_SERVER['HTTP_X_FORWARDED_FOR'] = '210.0.0.3, 2001:DB8::21f:5bff:febf:ce22:8a2e, 127.0.0.1';

			$this->assertEquals('127.0.0.1', ELBMetaData::getRemoteIp());
		}
	}