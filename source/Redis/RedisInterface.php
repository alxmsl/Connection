<?php

namespace Connection\Redis\Client;

/**
 * Redis commands interface
 * @author alxmsl
 * @date 11/4/12
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
     * Publish message to channel
     * @param string $channel channel name
     * @param string $message message
     * @return int the number of clients that received the message
     */
    public function publish($channel, $message);
}
