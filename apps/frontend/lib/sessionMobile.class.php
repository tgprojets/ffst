<?php

class sessionMobile extends sfStorage
{
  static protected
    $sessionIdRegenerated = false,
    $sessionStarted       = false;

  public function initialize($options = null)
  {
    $bExist = false;
    $cookieDefaults = session_get_cookie_params();

    $options = array_merge(array(
      'session_name'            => 'symfony',
      'session_id'              => null,
      'auto_start'              => true,
      'session_cookie_lifetime' => $cookieDefaults['lifetime'],
      'session_cookie_path'     => $cookieDefaults['path'],
      'session_cookie_domain'   => $cookieDefaults['domain'],
      'session_cookie_secure'   => $cookieDefaults['secure'],
      'session_cookie_httponly' => isset($cookieDefaults['httponly']) ? $cookieDefaults['httponly'] : false,
      'session_cache_limiter'   => null,
    ), $options);

    // initialize parent
    parent::initialize($options);

    if (isset($_GET['symfony']))
    {
        $sSession = $_GET['symfony'];
        if ($sSession != "") {
            session_name('symfony');
            session_id($sSession);
            session_start();
            $bExist = true;
        }
    }

    if ($bExist == false)
    {


        // set session name
        $sessionName = $this->options['session_name'];

        session_name($sessionName);

        if (!(boolean) ini_get('session.use_cookies') && $sessionId = $this->options['session_id'])
        {
          session_id($sessionId);
        }

        $lifetime = $this->options['session_cookie_lifetime'];
        $path     = $this->options['session_cookie_path'];
        $domain   = $this->options['session_cookie_domain'];
        $secure   = $this->options['session_cookie_secure'];
        $httpOnly = $this->options['session_cookie_httponly'];
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);

        if (null !== $this->options['session_cache_limiter'])
        {
          session_cache_limiter($this->options['session_cache_limiter']);
        }

        if ($this->options['auto_start'] && !self::$sessionStarted)
        {
          session_start();
          self::$sessionStarted = true;
        }
    }
  }
  /**
   * Reads data from this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   *
   * @return mixed Data associated with the key
   */
  public function read($key)
  {
    $retval = null;

    if (isset($_SESSION[$key]))
    {
      $retval = $_SESSION[$key];
    }

    return $retval;
  }

  /**
   * Removes data from this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   *
   * @return mixed Data associated with the key
   */
  public function remove($key)
  {
    $retval = null;

    if (isset($_SESSION[$key]))
    {
      $retval = $_SESSION[$key];
      unset($_SESSION[$key]);
    }

    return $retval;
  }

  /**
   * Writes data to this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param string $key   A unique key identifying your data
   * @param mixed  $data  Data associated with your key
   *
   */
  public function write($key, $data)
  {
    $_SESSION[$key] = $data;
  }

  /**
   * Regenerates id that represents this storage.
   *
   * @param  boolean $destroy Destroy session when regenerating?
   *
   * @return boolean True if session regenerated, false if error
   *
   */
  public function regenerate($destroy = false)
  {
    if (self::$sessionIdRegenerated)
    {
      return;
    }

    // regenerate a new session id once per object
    session_regenerate_id($destroy);

    self::$sessionIdRegenerated = true;
  }

  /**
   * Executes the shutdown procedure.
   *
   */
  public function shutdown()
  {
    // don't need a shutdown procedure because read/write do it in real-time
    session_write_close();
  }

}
