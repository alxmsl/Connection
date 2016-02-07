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

use alxmsl\Connection\Redis\Exception\ConnectException;
use alxmsl\Connection\Redis\Exception\ImpossibleValueException;
use alxmsl\Connection\Redis\Exception\KeyNotFoundException;
use alxmsl\Connection\Redis\Exception\ScriptExecutionException;
use Closure;
use Redis;

/**
 * Redis commands interface
 * @author alxmsl
 */
interface RedisInterface {
    /**
     * Increment key value
     * @param string $key key
     * @param int $value value for increment
     * @return int current value
     * @throws ConnectException exception on connection to redis instance
     */
    public function incr($key, $value = 1);

    /**
     * Decrement key value
     * @param string $key key
     * @param int $value value for increment
     * @return int current value
     * @throws ConnectException exception on connection to redis instance
     */
    public function decr($key, $value = 1);

    /**
     * Append string value
     * @param string $key key
     * @param string $value appended value
     * @return int length of a key after append
     * @throws ConnectException
     */
    public function append($key, $value);

    /**
     * Get key value
     * @param string $key key
     * @return mixed key value
     * @throws ConnectException exception on connection to redis instance
     * @throws KeyNotFoundException when key not found
     */
    public function get($key);

    /**
     * Get multiple keys values
     * @param array $keys keys
     * @return array values
     * @throws ConnectException exception on connection to redis instance
     */
    public function mget(array $keys);

    /**
     * Set key value
     * @param string $key key
     * @param mixed $value value
     * @param int $timeout ttl timeout in milliseconds
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function set($key, $value, $timeout = 0);

    /**
     * Set multiple key values
     * @param array $values key and values
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function mset(array $values);

    /**
     * Set key value if not exists
     * @param string $key key
     * @param mixed $value value
     * @return bool returns true, if operation complete succesfull, else false
     * @throws ConnectException exception on connection to redis instance
     */
    public function setnx($key, $value);

    /**
     * Set multiple key values
     * @param array $values key and values
     * @return bool operation result
     * @throws ConnectException exception on connection to redis instance
     */
    public function msetnx(array $values);

    /**
     * GetSet implementation
     * @param string $key key
     * @param mixed $value value
     * @return bool|mixed previous value of a key. If key did not set, method returns false
     * @throws ConnectException exception on connection to redis instance
     */
    public function getset($key, $value);

    /**
     * Delete key or keys
     * @param string|array $keys key or keys array
     * @return int count of deleted keys
     * @throws ConnectException exception on connection to redis instance
     */
    public function delete($keys);

    /**
     * Check if key exists
     * @param string $key key
     * @return bool check result
     * @throws ConnectException exception on connection to redis instance
     */
    public function exists($key);

    /**
     * Rename key
     * @param string $source current key name
     * @param string $destination needed key name
     * @return bool operation result. If false, source key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function rename($source, $destination);

    /**
     * Rename key if needed key name was not
     * @param string $source current key name
     * @param string $destination needed key name
     * @return bool operation result. If false, source key not found or needed key name found
     * @throws ConnectException exception on connection to redis instance
     */
    public function renamenx($source, $destination);

    /**
     * Get string length of a key
     * @param string $key key
     * @return int key value length
     * @throws ConnectException exception on connection to redis instance
     */
    public function strlen($key);

    /**
     * Set ttl for a key
     * @param string $key key
     * @param int $timeout ttl in milliseconds
     * @return bool operation result. If false ttl cound not be set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function expire($key, $timeout);

    /**
     * Set time of life for the key
     * @param string $key key
     * @param int $timestamp unix timestamp of time of death
     * @return bool operation result. If false timestamp cound not be set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function expireat($key, $timestamp);

    /**
     * Get ttl of the key
     * @param string $key key
     * @return int|bool ttl in milliseconds or false, if ttl is not set or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function ttl($key);

    /**
     * Remove ttl from the key
     * @param string $key key
     * @return bool if true ttl was removed successful, if false ttl did not set, or key not found
     * @throws ConnectException exception on connection to redis instance
     */
    public function persist($key);

    /**
     * Get key bit
     * @param string $key key
     * @param int $offset bit offset
     * @return int bit value at the offset
     * @throws ConnectException exception on connection to redis instance
     */
    public function getbit($key, $offset);

    /**
     * Set key bit
     * @param string $key key
     * @param int $offset bit offset
     * @param int $value bit value. May be 0 or 1
     * @return int bit value before operation complete
     * @throws ConnectException exception on connection to redis instance
     */
    public function setbit($key, $offset, $value);

    /**
     * Evaluate Lua code
     * @param string $code string of Lua code
     * @param array $arguments array of Lua script arguments
     * @return mixed code execution result
     * @throws ConnectException exception on connection to redis instance
     * @throws ScriptExecutionException when script execution faled
     */
    public function evaluate($code, array $arguments = array());

    /**
     * Evaluate Lua code by hash
     * @param string $sha SHA1 string of Lua code
     * @param array $arguments array of Lua script arguments
     * @return mixed code execution result
     * @throws ConnectException exception on connection to redis instance
     * @throws ScriptExecutionException when script execution faled
     */
    public function evalSha($sha, array $arguments = array());

    /**
     * Add member to the set
     * @param string $key key
     * @param mixed $member set member
     * @return int count of added members
     * @throws ConnectException exception on connection to redis instance
     */
    public function sadd($key, $member);

    /**
     * Pop (remove and return) a random member from the set
     * @param string $key key
     * @return mixed set member
     * @throws ConnectException exception on connection to redis instance
     */
    public function spop($key);

    /**
     * Return random member from the set
     * @param string $key key
     * @return mixed set member
     * @throws ConnectException exception on connection to redis instance
     */
    public function srandmember($key);

    /**
     * Returns size of the set
     * @param string $key set
     * @return int members count of the set
     * @throws ConnectException exception on connection to redis instance
     */
    public function scard($key);

    /**
     * Check that member is a member of the set
     * @param string $key key
     * @param mixed $member member
     * @return bool check result
     * @throws ConnectException exception on connection to redis instance
     */
    public function sismembers($key, $member);

    /**
     * Returns all members of the set
     * @param string $key key
     * @return array all members of the set
     * @throws ConnectException exception on connection to redis instance
     */
    public function smembers($key);

    /**
     * Remove member from the set
     * @param string $key key
     * @param mixed $member set member
     * @return int count of removed elements
     * @throws ConnectException exception on connection to redis instance
     */
    public function srem($key, $member);

    /**
     * Create difference set
     * @param string $destination key for result set
     * @param array $sources source keys
     * @return int size of result set
     * @throws ConnectException exception on connection to redis instance
     */
    public function sdiffstore($destination, array $sources);

    /**
     * Append value to a list
     * @param string $key key
     * @param mixed $member list member
     * @return int list length
     */
    public function rpush($key, $member);

    /**
     * Remove and get the last element in a list
     * @param string $key key
     * @return mixed last element from a list
     */
    public function rpop($key);

    /**
     * Prepend one or multiple values to a list
     * @param string $key key
     * @param mixed $member list member
     * @return int list length
     */
    public function lpush($key, $member);

    /**
     * Remove and get the first element in a list
     * @param string $key key
     * @return mixed first element from a list
     */
    public function lpop($key);

    /**
     * Returns list length
     * @param string $key list key
     * @throws ConnectException exception on connection to redis instance
     * @throws ImpossibleValueException when found key is not a list
     */
    public function llen($key);

    /**
     * Publish message to channel
     * @param string $channel channel name
     * @param string $message message
     * @return int the number of clients that received the message
     */
    public function publish($channel, $message);

    /**
     * Flush current database
     */
    public function flushDatabase();

    /**
     * Flush all instance databases
     */
    public function flushInstance();

    /**
     * Execute transaction method
     * @param callable $Commands function, that have Redis instance as a first argument.
     * Must returns boolean value for execute or discard all commands
     * @param int $mode transaction mode
     * @return bool transaction execution result
     */
    public function transaction(Closure $Commands, $mode = Redis::MULTI);

    /**
     * Return all keys matching the pattern
     * @param string $pattern search pattern
     * @return string[] found keys
     */
    public function keys($pattern);

    /**
     * Select redis database
     * @param int $database database number
     * @return bool selection result
     */
    public function select($database);

    /**
     * Start transaction
     * @param int $mode transaction mode: Redis::MULTI or Redis::PIPELINE
     * @return Redis current instance
     * @throws ConnectException when redis instance unavailable
     */
    public function multi($mode);

    /**
     * Watch the key
     * @param string $key watched key
     * @return bool watching result
     * @throws ConnectException when redis instance unavailable
     */
    public function watch($key);
}
