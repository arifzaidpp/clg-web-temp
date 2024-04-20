<?php

namespace ElfsightInstagramFeedApi;


class Options extends Core\Options {
    public function editorSettingsFilter($config) {
        $this->apiProxyUrl = rest_url($this->Helper->getPluginSlug());

        return parent::editorSettingsFilter($config);
    }

    public function addOptions() {
        parent::addOptions();

        $this->addOption(array(
            'id' => 'apiProxyUrl',
            'type' => 'hidden',
            'defaultValue' => $this->apiProxyUrl
        ));
    }

    public function modifyOptions() {
        $personalAccessTokenCustom = array(
            'authUrl' => $this->apiProxyUrl . '/api/auth?provider=instagram',
            'apiUrl' => $this->apiProxyUrl . '/api/facebook?cache_bypass=true',
            'useIdentity' => true,
            'title' => 'Instagram',
            'provider' => 'instagram',
            'icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMTZweCIgaGVpZ2h0PSIxNnB4IiB2aWV3Qm94PSIwIDAgMTYgMTYiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8dGl0bGU+aW5zdGEtaWNvbjwvdGl0bGU+CiAgICA8ZGVmcz4KICAgICAgICA8cGF0aCBkPSJNMTEuNTIwNTY0NywwIEw0LjM5MDk2NDcxLDAgQzEuOTY5Nzg4MjQsMCAwLDEuOTY5ODgyMzUgMCw0LjM5MTA1ODgyIEwwLDExLjUyMDY1ODggQzAsMTMuOTQxOTI5NCAxLjk2OTc4ODI0LDE1LjkxMTcxNzYgNC4zOTA5NjQ3MSwxNS45MTE3MTc2IEwxMS41MjA1NjQ3LDE1LjkxMTcxNzYgQzEzLjk0MTkyOTQsMTUuOTExNzE3NiAxNS45MTE3MTc2LDEzLjk0MTgzNTMgMTUuOTExNzE3NiwxMS41MjA2NTg4IEwxNS45MTE3MTc2LDQuMzkxMDU4ODIgQzE1LjkxMTcxNzYsMS45Njk4ODIzNSAxMy45NDE5Mjk0LDAgMTEuNTIwNTY0NywwIFogTTExLjUyMDU2NDcsMS40MTE3NjQ3MSBDMTMuMTYzMzg4MiwxLjQxMTc2NDcxIDE0LjUwMDA0NzEsMi43NDgzMjk0MSAxNC41MDAwNDcxLDQuMzkxMDU4ODIgTDE0LjUwMDA0NzEsMTEuNTIwNjU4OCBDMTQuNTAwMDQ3MSwxMy4xNjM0ODI0IDEzLjE2MzQ4MjQsMTQuNDk5OTUyOSAxMS41MjA2NTg4LDE0LjQ5OTk1MjkgTDQuMzkwOTY0NzEsMTQuNDk5OTUyOSBDMi43NDgyMzUyOSwxNC40OTk5NTI5IDEuNDExNzY0NzEsMTMuMTYzNDgyNCAxLjQxMTc2NDcxLDExLjUyMDY1ODggTDEuNDExNzY0NzEsNC4zOTEwNTg4MiBDMS40MTE3NjQ3MSwyLjc0ODMyOTQxIDIuNzQ4MjM1MjksMS40MTE3NjQ3MSA0LjM5MDk2NDcxLDEuNDExNzY0NzEgTDExLjUyMDU2NDcsMS40MTE3NjQ3MSBaIE03Ljk1NTg1ODgyLDMuODU2IEM1LjY5NTA1ODgyLDMuODU2IDMuODU1ODExNzYsNS42OTUyNDcwNiAzLjg1NTgxMTc2LDcuOTU2MDQ3MDYgQzMuODU1ODExNzYsMTAuMjE2NzUyOSA1LjY5NTA1ODgyLDEyLjA1NTkwNTkgNy45NTU4NTg4MiwxMi4wNTU5MDU5IEMxMC4yMTY2NTg4LDEyLjA1NTkwNTkgMTIuMDU1OTA1OSwxMC4yMTY3NTI5IDEyLjA1NTkwNTksNy45NTYwNDcwNiBDMTIuMDU1OTA1OSw1LjY5NTI0NzA2IDEwLjIxNjY1ODgsMy44NTYgNy45NTU4NTg4MiwzLjg1NiBaIE03Ljk1NTg1ODgyLDUuMjY3NjcwNTkgQzkuNDM4MjExNzYsNS4yNjc2NzA1OSAxMC42NDQxNDEyLDYuNDczNiAxMC42NDQxNDEyLDcuOTU1OTUyOTQgQzEwLjY0NDE0MTIsOS40MzgyMTE3NiA5LjQzODExNzY1LDEwLjY0NDA0NzEgNy45NTU4NTg4MiwxMC42NDQwNDcxIEM2LjQ3MzYsMTAuNjQ0MDQ3MSA1LjI2NzU3NjQ3LDkuNDM4MjExNzYgNS4yNjc1NzY0Nyw3Ljk1NTk1Mjk0IEM1LjI2NzU3NjQ3LDYuNDczNiA2LjQ3MzUwNTg4LDUuMjY3NjcwNTkgNy45NTU4NTg4Miw1LjI2NzY3MDU5IFogTTEyLjIyNzg1ODgsMi42NTg5MTc2NSBDMTEuOTU1ODU4OCwyLjY1ODkxNzY1IDExLjY4ODY1ODgsMi43NjkwMzUyOSAxMS40OTY1NjQ3LDIuOTYxOTc2NDcgQzExLjMwMzUyOTQsMy4xNTM5NzY0NyAxMS4xOTI1NjQ3LDMuNDIxMjcwNTkgMTEuMTkyNTY0NywzLjY5NDIxMTc2IEMxMS4xOTI1NjQ3LDMuOTY2MzA1ODggMTEuMzAzNjIzNSw0LjIzMzUwNTg4IDExLjQ5NjU2NDcsNC40MjY0NDcwNiBDMTEuNjg4NTY0Nyw0LjYxODQ0NzA2IDExLjk1NTg1ODgsNC43Mjk1MDU4OCAxMi4yMjc4NTg4LDQuNzI5NTA1ODggQzEyLjUwMDgsNC43Mjk1MDU4OCAxMi43NjcxNTI5LDQuNjE4NDQ3MDYgMTIuOTYwMDk0MSw0LjQyNjQ0NzA2IEMxMy4xNTMwMzUzLDQuMjMzNTA1ODggMTMuMjYzMTUyOSwzLjk2NjIxMTc2IDEzLjI2MzE1MjksMy42OTQyMTE3NiBDMTMuMjYzMTUyOSwzLjQyMTI3MDU5IDEzLjE1MzAzNTMsMy4xNTM5NzY0NyAxMi45NjAwOTQxLDIuOTYxOTc2NDcgQzEyLjc2ODA5NDEsMi43NjkwMzUyOSAxMi41MDA4LDIuNjU4OTE3NjUgMTIuMjI3ODU4OCwyLjY1ODkxNzY1IFoiIGlkPSJwYXRoLTEiPjwvcGF0aD4KICAgICAgICA8bGluZWFyR3JhZGllbnQgeDE9Ijk4LjEyNTk3NTQlIiB5MT0iMS44NzQwMjQ2MSUiIHgyPSIxLjQzNDk0ODk4JSIgeTI9Ijk4LjU2NTA1MSUiIGlkPSJsaW5lYXJHcmFkaWVudC0zIj4KICAgICAgICAgICAgPHN0b3Agc3RvcC1jb2xvcj0iIzU4NDZGRiIgb2Zmc2V0PSIwJSI+PC9zdG9wPgogICAgICAgICAgICA8c3RvcCBzdG9wLWNvbG9yPSIjRkY0OTQyIiBvZmZzZXQ9IjY2LjAyODYxODQlIj48L3N0b3A+CiAgICAgICAgICAgIDxzdG9wIHN0b3AtY29sb3I9IiNGRkFDMkQiIG9mZnNldD0iMTAwJSI+PC9zdG9wPgogICAgICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgICA8L2RlZnM+CiAgICA8ZyBpZD0iaW5zdGEtaWNvbiIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPGcgaWQ9ImNtcy1pY29uL2luc3RhZ3JhbS0yIj4KICAgICAgICAgICAgPG1hc2sgaWQ9Im1hc2stMiIgZmlsbD0id2hpdGUiPgogICAgICAgICAgICAgICAgPHVzZSB4bGluazpocmVmPSIjcGF0aC0xIj48L3VzZT4KICAgICAgICAgICAgPC9tYXNrPgogICAgICAgICAgICA8dXNlIGlkPSJDb21iaW5lZC1TaGFwZSIgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJub256ZXJvIiB4bGluazpocmVmPSIjcGF0aC0xIj48L3VzZT4KICAgICAgICAgICAgPGcgaWQ9Ikdyb3VwIiBtYXNrPSJ1cmwoI21hc2stMikiIGZpbGw9InVybCgjbGluZWFyR3JhZGllbnQtMykiPgogICAgICAgICAgICAgICAgPGcgaWQ9ImNvbG9yIj4KICAgICAgICAgICAgICAgICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlLTMiIHg9IjAiIHk9IjAiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiI+PC9yZWN0PgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4='
        );
        $businessAccessTokenCustom = array(
            'authUrl' => $this->apiProxyUrl . '/api/auth?provider=facebook',
            'apiUrl' => $this->apiProxyUrl . '/api/facebook?cache_bypass=true',
            'useIdentity' => true,
            'title' => 'Facebook',
            'provider' => 'facebook',
            'appConfig' => 'instagram',
            'scopes' => 'instagram_basic,pages_show_list',
            'icon' => 'data:image/svg+xml;base64,CgogICAgICAgICAgICAgICAgICAgIDxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0IiBmaWxsPSIjZmZmZmZmIiB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIj48cGF0aCBkPSJNMjM0Ljc0NyA1NTQuNTY1VjM0Ni42MjRoMTUyLjUxMnYtODEuMDJDMzg3LjI1OSAxMjUuODkzIDQ5Mi4yLjA2OCA2MjEuMTg2LjA2OGgxNjguMDY4djIwNy45MThINjIxLjE4NmMtMTguNDEyIDAtMzkuODUgMjIuMzI5LTM5Ljg1IDU1LjgwMXY4Mi44MjZoMjA3LjkxOHYyMDcuOTI5SDU4MS4zMzZ2NDY5LjM5SDM4Ny4yNDdWNTU0LjU2NEgyMzQuNzQ2eiI+PC9wYXRoPjwvc3ZnPgogICAgICAgICAgICAgICAg'
        );
        $businessAccountCustom = array(
            'authUrl' => $this->apiProxyUrl . '/api/auth-set?provider=facebook',
            'apiUrl' => $this->apiProxyUrl . '/api/facebook?cache_bypass=true',
            'provider' => 'facebook',
            'type' => 'instagram-connected-account-select',
            'userAccessTokenProperty' => 'businessAccessToken',
        );

        $this->modifyOption('personalAccessToken', array(
            'token' => $personalAccessTokenCustom
        ));
        $this->modifyOption('businessAccessToken', array(
            'token' => $businessAccessTokenCustom
        ));
        $this->modifyOption('businessAccount', array(
            'custom' => $businessAccountCustom
        ));
    }

    public function shortcodeOptionsFilter($options) {
        $this->apiProxyUrl = rest_url($this->Helper->getPluginSlug());

        $options = parent::shortcodeOptionsFilter($options);

        if (is_array($options)) {
            $options['apiProxyUrl'] = $this->apiProxyUrl;
        }

        return $options;
    }

    public function widgetOptionsFilter($options_json) {
        $options_json = parent::widgetOptionsFilter($options_json);
        $options = json_decode($options_json, true);

        if (is_array($options)) {
            unset($options['apiProxyUrl']);
        }

        return json_encode($options);
    }
}
