<?php

namespace App\Traits\Booking;


/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \App\Models\Booking
 */
trait CanConstructUrlToShowDetail
{
    /**
     * Construct the URL for the guest ticket page.
     *
     * @return string The constructed URL
     */
    public function constructGuestTicketPageUrl()
    {
        return route('payment.show', $this->id) . '?' . $this->buildTokenQueryString();
    }

    /**
     * Generates a query string for HTTP request.
     *
     * @return string The generated query string
     */
    public function buildTokenQueryString()
    {
        // Generate token
        $token = $this->createToken();

        // Build query parameters
        return http_build_query([
            "token"     => $token,
            "code"      => $this->code,
            "timestamp" => strtotime($this->from_date ?? $this->created_at) . strtotime($this->until_date ?? $this->created_at),
        ]);
    }

    /**
     * Create a unique token based on app key, transaction code, booking date, and class short name
     *
     * @return string
     */
    private function createToken()
    {
        // Combine the app key, transaction code, booking date timestamp, and class short name to generate the token
        return sha1(config('app.key') . $this->code . strtotime($this->from_date ?? $this->created_at) . strtotime($this->until_date ?? $this->created_at));
    }

    /**
     * Check if the provided token matches the generated token
     *
     * @param string $token
     * @return bool
     */
    public function validateToken($token)
    {
        // Check if the generated token matches the provided token
        return $token === $this->createToken();
    }
}
