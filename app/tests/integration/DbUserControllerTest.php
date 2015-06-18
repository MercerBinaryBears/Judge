<?php

class DbUserControllerTest extends DbTestCase
{
    public function testInvalidLogin()
    {
        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $response = $this->action('POST', 'Judge\Controllers\UserController@login', [
            'username' => 'USERNAME',
            'password' => 'PASSWORD'
        ]);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
        $this->assertTrue(Session::has('_old_input'));
    }

    public function testLogin()
    {
        Auth::shouldReceive('attempt')->once()->andReturn(true);
        
        $response = $this->action('POST', 'Judge\Controllers\UserController@login', [
            'username' => 'USERNAME',
            'password' => 'PASSWORD'
        ]);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);

        $this->assertFalse(Session::has('_old_input'));
    }

    public function testLogout()
    {
        Auth::shouldReceive('logout')->once();

        $response = $this->action('GET', 'Judge\Controllers\UserController@logout');
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
    }
}
