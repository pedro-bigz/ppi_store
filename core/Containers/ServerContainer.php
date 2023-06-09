<?php namespace Core\Containers;

class ServerContainer extends ItemContainer
{
    public function getHeaders()
    {
        $headers = [];
        if (isset($this->items['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $this->items['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = $this->items['PHP_AUTH_PW'] ?? '';
        }
        if (isset($this->items['X-Requested-With'])) {
            $headers['X-Requested-With'] = $this->items['X-Requested-With'];
        }

        foreach ($this->items as $key => $value) {
            if (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'])) {
                $headers[$key] = $value;
            } else if (str_starts_with($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } 
        }

        return $headers;
    }
}
