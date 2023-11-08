<?php

namespace App\Services;

use CodeIgniter\Config\Services;

class CsrfService
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    /**
     * Generates a CSRF token and stores it in the session.
     *
     * @return string The generated CSRF token
     */
    public function generateToken()
    {
        $token = csrf_hash();
        $this->session->set('csrf_token', $token);
        return $token;
    }

    /**
     * Validates the provided CSRF token against the stored session token.
     *
     * @param string|null $token The token to validate
     * @return bool Validation result: true if the token is valid, false otherwise
     */
    public function validateToken($token)
    {
        if ($token && $this->session->get('csrf_token') === $token) {
            $this->session->remove('csrf_token');
            return true;
        }

        return false;
    }
}
