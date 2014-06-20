<?php
/**
 * A package to inteface with Appcelerator Cloud Services
 *
 * @package   Acs
 * @version   1.0.0
 * @author    Joel Herron
 * @license   MIT License
 * @copyright 2014 Joel Herron
 * @link      http://github.com/h3r2on/laravel-acs
 */

namespace H3r2on\Acs\Test;

use H3r2on\Acs\Acs;
use Mockery as m;

class AcsTest extends \PHPUnit_Framework_TestCase
{

  public function teardown()
  {
    m::close();
  }

  public function testDelete()
  {

  }

  public function testGet()
  {
    $response = ACS::get('users/show.json');
    $content = json_decode($response);
    $this->assertSame(JSON_ERROR_NONE, json_last_error());
    $this->assertSame('ok',$content->meta->status);
  }

  public function testPost()
  {

  }

  public function testPut()
  {

  }
}
