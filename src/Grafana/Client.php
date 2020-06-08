<?php

namespace Larapress\Reports\Grafana;

use Exception;
use Illuminate\Support\Facades\Http;
use Larapress\Core\Exceptions\AppException;

class Client
{
    /**
     * Undocumented function
     *
     * @param String $path
     * @return String
     */
    public function getUrlForApi(String $path)
    {
        return config('larapress.reports.grafana.base') . $path;
    }

    /**
     * Create new team and return json data
     *
     * @param String $name
     * @param String $email
     * @return Object
     */
    public function createTeam(String $name, String $email)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->post($this->getUrlForApi('/api/admin/users'), [
                'name' => $name,
                'email' => $email
            ]);

        $response->throw();
        return $response->json();
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param String $theme
     * @param integer $homeDashboardId
     * @param String $timezone
     * @return void
     */
    public function updateTeamPreference(int $id, String $theme, int $homeDashboardId, String $timezone)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->post($this->getUrlForApi('/api/teams/' . $id . '/preferences'), [
                'theme' => $theme,
                'homeDashboardId' => $homeDashboardId,
                'timezone' => $timezone
            ]);

        return $response->successful();
    }

    /**
     * Edit existing team with id and return json data
     *
     * @param integer $id
     * @param String $name
     * @param String $email
     * @return void
     */
    public function updateTeam(int $id, String $name, String $email)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->post($this->getUrlForApi('/api/teams'), [
                'name' => $name,
                'email' => $email
            ]);

        return $response->successful();
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return bool
     */
    public function removeTeam(int $id)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->delete($this->getUrlForApi('/api/teams/' . $id));

        return $response->successful();
    }

    /**
     * Undocumented function
     *
     * @param integer $userId
     * @param integer $teamId
     * @return bool
     */
    public function addTeamMember(int $userId, int $teamId)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->post($this->getUrlForApi('/api/teams/' . $teamId . '/members'), [
                'userId' => $userId
            ]);

        return $response->successful();
    }

    /**
     * Undocumented function
     *
     * @param integer $userId
     * @param integer $teamId
     * @return bool
     */
    public function removeTeamMember(int $userId, int $teamId)
    {
        $response = Http::withToken(config('larapress.reports.grafana.auth_token'))
            ->delete($this->getUrlForApi('/api/teams/' . $teamId . '/members/' . $userId));

        return $response->successful();
    }

    /**
     * Undocumented function
     *
     * @param String $name
     * @param String $email
     * @param String $username
     * @param String $password
     * @return Object
     */
    public function addUser(String $name, String $email, String $username, String $password)
    {
        $response = Http::withBasicAuth(config('larapress.reports.grafana.admin_username'), config('larapress.reports.grafana.admin_password'))
            ->post($this->getUrlForApi('/api/admin/users'), [
                'name' => $name,
                'email' => $email,
                'login' => $username,
                'password' => $password,
                'OrgId' => config('larapress.reports.grafana.orgId')
            ]);

        $response->throw();
        return $response->json();
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param String $password
     * @return bool
     */
    public function updateUserPassword(int $id, String $password)
    {
        $response = Http::withBasicAuth(config('larapress.reports.grafana.admin_username'), config('larapress.reports.grafana.admin_password'))
            ->post($this->getUrlForApi('/api/admin/users/' . $id . '/password'), [
                'password' => $password,
            ]);

        return $response->successful();
    }
}
