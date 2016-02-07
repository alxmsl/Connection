<?php
/*
 * Copyright 2015-2016 Alexey Maslov <alexey.y.maslov@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace alxmsl\Connection\Redis;

use alxmsl\Connection\AbstractConnection;
use alxmsl\Connection\Redis\Exception\ConnectException;
use alxmsl\Connection\Redis\Exception\ImpossibleValueException;
use alxmsl\Connection\Redis\Exception\KeyNotFoundException;
use alxmsl\Connection\Redis\Exception\RedisNotConfiguredException;
use alxmsl\Connection\Redis\Exception\ScriptExecutionException;
use alxmsl\Connection\Redis\Exception\TriesOverConnectException;
use Closure;
use RedisException;
use Redis;

/**
 * Class for redis client
 * @author alxmsl
 */
final class Connection extends AbstractConnection implements RedisInterface {
    /**
     * @var \Redis phpredis object instance
     */
    private $Redis = null;

    /**
     * Getter of phpredis object
     * @return \Redis phpredis object instance
     * @throws RedisNotConfiguredException if any of required redis connect parameters are loose
     */
    private function getRedis() {
        if (is_null($this->Redis)) {
            if ($this->isConfigured()) {
                $this->Redis = new \Redis();
                $this->connect();
            } else {
                throw new RedisNotConfiguredException();
            }
        }
        return $this->Redis;
    }

    /**
     * Сonnect to the redis instance
     * @return bool connection result. Always true.
     * @throws ConnectException if connection could not established by RedisException cause
     * @throws TriesOverConnectException if connection could not established because tries was over
     */
    public function connect() {
        $count = 0;
        do {
            $count += 1;
            try {
                if ($this->isPersistent()) {
                    $result = $this->Redis->pconnect($this->getHost(), $this->getPort(), $this->getConnectTimeout());
                } else {
                    $result = $this->Redis->connect($this->getHost(), $this->getPort(), $this->getConnectTimeout());
                }
            } catch (RedisException $ex) {
                throw new ConnectException();
            }
            if ($result === true) {
                return true;
            }
        } while ($count < $this->getConnectTries());

        $this->Redis = null;
        throw new TriesOverConnectException();
    }

    /**
     * Disconnect from redis instance
     */
    public function disconnect() {
        $this->getRedis()->close();
    }

    /**
     * Reconnect to redis instance
     */
    public function reconnect() {
        $this->disconnect();
        return $this->connect();
    }

    /**
     * Increment key value
     * @param string $key key
     * @param int $value value for increment
     * @return int current value
     * @throws ConnectException exception on connection to redis instance
     */
    public function incr($key, $value = 1) {
        $value = (int) $value;
        try {
            $result = ($value > 1)
                ? $this->getRedis()->incrBy($key, $value)
                : $this->getRedis()->incr($key);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Decrement key value
     * @param string $key key
     * @param int $value value for increment
     * @return int current value
     * @throws ConnectException exception on connection to redis instance
     */
    public function decr($key, $value = 1) {
        $value = (int) $value;
        try {
            $result = ($value > 1)
                ? $this->getRedis()->decrBy($key, $value)
                : $this->getRedis()->decr($key);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Append string value
     * @param string $key key
     * @param string $value appended value
     * @return int length of a key after append
     * @throws ConnectException
     */
    public function append($key, $value) {
        try {
            $result = $this->getRedis()->append($key, $value);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Get key value
     * @param string $key key
     * @return mixed key value
     * @throws ConnectException exception on connection to redis instance
     * @throws KeyNotFoundException when key not found
     */
    public function get($key) {
        try {
            $result = $this->getRedis()->get($key);
            if ($result === false) {
                throw new KeyNotFoundException();
            }
            return $result;
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Get multiple keys values
     * @param array $keys keys
     * @return array values
     * @throws ConnectException exception on connection to redis instance
     */
    public function mget(array $keys) {
        try {
            $result = $this->getRedis()->mGet($keys);
            if ($result !== false) {
                return array_combine($keys, $result);
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set key value
     * @param string $key key
     * @param mixed $value value
     * @param int $timeout ttl timeout in milliseconds
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function set($key, $value, $timeout = 0) {
        try {
            $result = ($timeout == 0)
                ? $this->getRedis()->set($key, $value)
                : $this->getRedis()->psetex($key, $timeout, $value);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set multiple key values
     * @param array $values key and values
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function mset(array $values) {
        try {
            return $this->getRedis()->mset($values);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set key value if not exists
     * @param string $key key
     * @param mixed $value value
     * @return bool returns true, if operation complete succesfull, else false
     * @throws ConnectException exception on connection to redis instance
     */
    public function setnx($key, $value) {
        try {
            return $this->getRedis()->setnx($key, $value);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set multiple key values
     * @param array $values key and values
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function msetnx(array $values) {
        try {
            return $this->getRedis()->msetnx($values);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * GetSet implementation
     * @param string $key key
     * @param mixed $value value
     * @return bool|mixed previous value of a key. If key did not set, method returns false
     * @throws ConnectException exception on connection to redis instance
     */
    public function getset($key, $value) {
        try {
            return $this->getRedis()->getSet($key, $value);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Delete key or keys
     * @param string|array $keys key or keys array
     * @return int count of deleted keys
     * @throws ConnectException exception on connection to redis instance
     */
    public function delete($keys) {
        try {
            $result = $this->getRedis()->delete($keys);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Check if key exists
     * @param string $key key
     * @return bool check result
     * @throws ConnectException exception on connection to redis instance
     */
    public function exists($key) {
        try {
            return $this->getRedis()->exists($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Rename key
     * @param string $source current key name
     * @param string $destination needed key name
     * @return bool operation result. If false, source key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function rename($source, $destination) {
        try {
            return $this->getRedis()->rename($source, $destination);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Rename key if needed key name was not
     * @param string $source current key name
     * @param string $destination needed key name
     * @return bool operation result. If false, source key not found or needed key name found
     * @throws ConnectException exception on connection to redis instance
     */
    public function renamenx($source, $destination) {
        try {
            return $this->getRedis()->renamenx($source, $destination);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Get string length of a key
     * @param string $key key
     * @return int key value length
     * @throws ConnectException exception on connection to redis instance
     */
    public function strlen($key) {
        try {
            $result = $this->getRedis()->strlen($key);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set ttl for a key
     * @param string $key key
     * @param int $timeout ttl in milliseconds
     * @return bool operation result. If false ttl cound not be set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function expire($key, $timeout) {
        try {
            return $this->getRedis()->pexpire($key, $timeout);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set time of life for the key
     * @param string $key key
     * @param int $timestamp unix timestamp of time of death
     * @return bool operation result. If false timestamp cound not be set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function expireat($key, $timestamp) {
        try {
            return $this->getRedis()->expireat($key, $timestamp);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Get ttl of the key
     * @param string $key key
     * @return int|bool ttl in milliseconds or false, if ttl is not set or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function ttl($key) {
        try {
            $result = $this->getRedis()->pttl($key);
            return ($result != -1) ? $result : false;
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Remove ttl from the key
     * @param string $key key
     * @return bool if true ttl was removed successful, if false ttl did not set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function persist($key) {
        try {
            return $this->getRedis()->persist($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Get key bit
     * @param string $key key
     * @param int $offset bit offset
     * @return int bit value at the offset
     * @throws ConnectException exception on connection to redis instance
     */
    public function getbit($key, $offset) {
        $offset = (int) $offset;
        try {
            $result = $this->getRedis()->getBit($key, $offset);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Set key bit
     * @param string $key key
     * @param int $offset bit offset
     * @param int $value bit value. May be 0 or 1
     * @return int bit value before operation complete
     * @throws ConnectException exception on connection to redis instance
     */
    public function setbit($key, $offset, $value) {
        $offset = (int) $offset;
        $value = (int) (bool) $value;
        try {
            $result = $this->getRedis()->setBit($key, $offset, $value);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Evaluate Lua code
     * @param string $code string of Lua code
     * @param array $arguments array of Lua script arguments
     * @return mixed code execution result
     * @throws ConnectException exception on connection to redis instance
     * @throws ScriptExecutionException when script execution faled
     */
    public function evaluate($code, array $arguments = array()) {
        try {
            if (empty($arguments)) {
                $result = $this->getRedis()->eval($code);
            } else {
                $result = $this->getRedis()->eval($code, $arguments, count($arguments));
            }

            $lastError = $this->getRedis()->getLastError();
            $this->getRedis()->clearLastError();
            if (is_null($lastError)) {
                return $result;
            }
            throw new ScriptExecutionException($lastError);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Evaluate Lua code by hash
     * @param string $sha SHA1 string of Lua code
     * @param array $arguments array of Lua script arguments
     * @return mixed code execution result
     * @throws ConnectException exception on connection to redis instance
     * @throws ScriptExecutionException when script execution faled
     */
    public function evalSha($sha, array $arguments = array()) {
        try {
            if (empty($arguments)) {
                $result = $this->getRedis()->evalSha($sha);
            } else {
                $result = $this->getRedis()->evalSha($sha, $arguments, count($arguments));
            }

            $lastError = $this->getRedis()->getLastError();
            $this->getRedis()->clearLastError();
            if (is_null($lastError)) {
                return $result;
            }
            throw new ScriptExecutionException($lastError);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Add member to the set
     * @param string $key key
     * @param mixed $member set member
     * @return int count of added members
     * @throws ConnectException exception on connection to redis instance
     */
    public function sadd($key, $member) {
        try {
            $result = $this->getRedis()->sAdd($key, $member);
            if ($result !== false) {
                return $result;
            }
            throw new ImpossibleValueException();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Pop (remove and return) a random member from the set
     * @param string $key key
     * @return mixed set member
     * @throws ConnectException exception on connection to redis instance
     */
    public function spop($key) {
        try {
            return $this->getRedis()->sPop($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Return random member from the set
     * @param string $key key
     * @return mixed set member
     * @throws ConnectException exception on connection to redis instance
     */
    public function srandmember($key) {
        try {
            return $this->getRedis()->sRandMember($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Returns size of the set
     * @param string $key set
     * @return int members count of the set
     * @throws ConnectException exception on connection to redis instance
     */
    public function scard($key) {
        try {
            return $this->getRedis()->sCard($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Check that member is a member of the set
     * @param string $key key
     * @param mixed $member member
     * @return bool check result
     * @throws ConnectException exception on connection to redis instance
     */
    public function sismembers($key, $member) {
        try {
            return $this->getRedis()->sIsMember($key, $member);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Returns all members of the set
     * @param string $key key
     * @return array all members of the set
     * @throws ConnectException exception on connection to redis instance
     */
    public function smembers($key) {
        try {
            return $this->getRedis()->sMembers($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Remove member from the set
     * @param string $key key
     * @param mixed $member set member
     * @return int count of removed elements
     * @throws ConnectException exception on connection to redis instance
     */
    public function srem($key, $member) {
        try {
            return $this->getRedis()->sRem($key, $member);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Create difference set
     * @param string $destination key for result set
     * @param array $sources source keys
     * @return int size of result set
     * @throws ConnectException exception on connection to redis instance
     */
    public function sdiffstore($destination, array $sources) {
        try {
            return call_user_func_array(array(
                $this->getRedis(),
                'sDiffStore',
            ), array_merge(array($destination), $sources));
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Append value to a list
     * @param string $key key
     * @param mixed $member list member
     * @return int list length
     */
    public function rpush($key, $member) {
        try {
            return $this->getRedis()->rPush($key, $member);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Remove and get the last element in a list
     * @param string $key key
     * @return mixed last element from a list
     */
    public function rpop($key) {
        try {
            return $this->getRedis()->rPop($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Prepend one or multiple values to a list
     * @param string $key key
     * @param mixed $member list member
     * @return int list length
     */
    public function lpush($key, $member) {
        try {
            return $this->getRedis()->lPush($key, $member);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Remove and get the first element in a list
     * @param string $key key
     * @return mixed first element from a list
     */
    public function lpop($key) {
        try {
            return $this->getRedis()->lPop($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Returns list length
     * @param string $key list key
     * @throws ConnectException exception on connection to redis instance
     * @throws ImpossibleValueException when found key is not a list
     */
    public function llen($key) {
        try {
            $length = $this->getRedis()->lLen($key);
            if ($length === false) {
                throw new ImpossibleValueException();
            }
            return $length;
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Publish message to channel
     * @param string $channel channel name
     * @param string $message message
     * @return int the number of clients that received the message
     */
    public function publish($channel, $message) {
        try {
            return $this->getRedis()->publish($channel, $message);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Flush current database
     */
    public function flushDatabase() {
        try {
            return $this->getRedis()->flushDB();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Flush all instance databases
     */
    public function flushInstance() {
        try {
            return $this->getRedis()->flushAll();
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Execute transaction method
     * @param callable $Commands function, that have Redis instance as a first argument.
     * Must returns boolean value for execute or discard all commands
     * @param string $mode transaction mode
     * @return array|false transaction execution result or false on failure
     */
    public function transaction(Closure $Commands, $mode = Redis::MULTI) {
        try {
            $Instance = $this->multi($mode);
            $result = $Commands($Instance);
            if ($result == true) {
                return $Instance->exec();
            } else {
                $Instance->discard();
                return false;
            }
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Return all keys matching the pattern
     * @param string $pattern search pattern
     * @return string[] found keys
     */
    public function keys($pattern) {
        try {
            return $this->getRedis()->keys($pattern);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * Select redis database
     * @param int $database database number
     * @return bool selection result
     */
    public function select($database) {
        try {
            return $this->getRedis()->select($database);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * @inheritdoc
     */
    public function multi($mode = Redis::MULTI) {
        try {
            return $this->getRedis()->multi($mode);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }

    /**
     * @inheritdoc
     */
    public function watch($key) {
        try {
            return $this->getRedis()->watch($key);
        } catch (RedisException $ex) {
            throw new ConnectException();
        }
    }
}
