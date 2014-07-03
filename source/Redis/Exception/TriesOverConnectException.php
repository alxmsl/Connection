<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection\Redis\Exception;

/**
 * Exception for tries over when connecting to Redis instance
 * @author alxmsl
 * @date 7/3/14
 */ 
final class TriesOverConnectException extends ConnectException {}
 