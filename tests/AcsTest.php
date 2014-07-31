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
use \Mockery as m;

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
    $mock = m::mock('alias:H3r2on\Acs');
    $mock->shouldReceive('get')
         ->with('users/show.json')
         ->andReturnUsing(function($data){
          if ($data->meta->status === 'ok') {
            return true;
          } else {
            return false;
          }
         });
  }

  public function testPost()
  {
    
  }

  public function testPut()
  {

  }
}
