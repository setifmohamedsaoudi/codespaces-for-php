
<?php

/*
 * Pheditor
 * PHP file editor
 * Hamid Samak
 * https://github.com/pheditor/pheditor
 * Release under MIT license
 */

define('PASSWORD', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec');
define('DS', DIRECTORY_SEPARATOR);
define('MAIN_DIR', realpath(__DIR__));
define('VERSION', '2.0.1');
define('LOG_FILE', MAIN_DIR . DS . '.phedlog');
define('SHOW_PHP_SELF', false);
define('SHOW_HIDDEN_FILES', false);
define('ACCESS_IP', '');
define('HISTORY_PATH', MAIN_DIR . DS . '.phedhistory');
define('MAX_HISTORY_FILES', 5);
define('WORD_WRAP', true);
define('PERMISSIONS', 'newfile,newdir,editfile,deletefile,deletedir,renamefile,renamedir,changepassword,uploadfile,terminal'); // empty means all
define('PATTERN_FILES', '/^[A-Za-z0-9-_.\/]*\.(txt|php|htm|html|js|css|tpl|md|xml|json)$/i'); // empty means no pattern
define('PATTERN_DIRECTORIES', '/^((?!backup).)*$/i'); // empty means no pattern
define('TERMINAL_COMMANDS', 'ls,ll,cp,rm,mv,whoami,pidof,pwd,whereis,kill,php,date,cd,mkdir,chmod,chown,rmdir,touch,cat,git,find,grep,echo,tar,zip,unzip,whatis,df,help,locate,pkill,du,updatedb,composer,exit');
define('EDITOR_THEME', ''); // e.g. monokai
define('DEFAULT_DIR_PERMISSION', 0755);
define('DEFAULT_FILE_PERMISSION', 0644);
define('LOCAL_ASSETS', false); // if true you should run `npm i` to download required libraries

$assets = [
	'cdn' => [
		'css' => [
			'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/themes/default/style.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/lint/lint.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/dialog/dialog.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/theme/monokai.css',
			empty(EDITOR_THEME) ? '' : 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/theme/' . EDITOR_THEME . '.css',
			'https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
		],
		'js' => [
			'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js',
			'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/jstree.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/javascript/javascript.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/css/css.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/php/php.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/xml/xml.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/htmlmixed/htmlmixed.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/markdown/markdown.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/clike/clike.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/jshint/2.13.6/jshint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/jsonlint/1.6.0/jsonlint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/lint/lint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/lint/javascript-lint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/lint/json-lint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/lint/css-lint.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/search/search.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/search/searchcursor.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/search/jump-to-line.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/addon/dialog/dialog.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/js-sha512/0.8.0/sha512.min.js'
		],
	],
	'local' => [
		'css' => [
			'node_modules/bootstrap/dist/css/bootstrap.min.css',
			'node_modules/jstree/dist/themes/default/style.min.css',
			'node_modules/codemirror/lib/codemirror.css',
			'node_modules/codemirror/addon/lint/lint.css',
			'node_modules/codemirror/addon/dialog/dialog.css',
			'node_modules/codemirror//theme/monokai.css',
			empty(EDITOR_THEME) ? '' : 'node_modules/codemirror/theme/' . EDITOR_THEME . '.css',
			'node_modules/izitoast/dist/css/iziToast.min.css',
			'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
		],
		'js' => [
			'node_modules/jquery/dist/jquery.min.js',
			'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
			'node_modules/jstree/dist/jstree.min.js',
			'node_modules/codemirror/lib/codemirror.js',
			'node_modules/codemirror/mode/javascript/javascript.js',
			'node_modules/codemirror/mode/css/css.js',
			'node_modules/codemirror/mode/php/php.js',
			'node_modules/codemirror/mode/xml/xml.js',
			'node_modules/codemirror/mode/htmlmixed/htmlmixed.js',
			'node_modules/codemirror/mode/markdown/markdown.js',
			'node_modules/codemirror/mode/clike/clike.js',
			'node_modules/jshint/dist/jshint.js',
			// 'node_modules/jsonlint/lib/jsonlint.js',
			'node_modules/codemirror/addon/lint/lint.js',
			'node_modules/codemirror/addon/lint/javascript-lint.js',
			// 'node_modules/codemirror/addon/lint/json-lint.js',
			'node_modules/codemirror/addon/lint/css-lint.js',
			'node_modules/codemirror/addon/search/search.js',
			'node_modules/codemirror/addon/search/searchcursor.js',
			'node_modules/codemirror/addon/search/jump-to-line.js',
			'node_modules/codemirror/addon/dialog/dialog.js',
			'node_modules/izitoast/dist/js/iziToast.min.js',
			'node_modules/js-sha512/build/sha512.min.js'
		],
	],
];

if (empty(ACCESS_IP) === false && ACCESS_IP != $_SERVER['REMOTE_ADDR']) {
	die('Your IP address is not allowed to access this page.');
}

if (file_exists(LOG_FILE)) {
	$log = unserialize(file_get_contents(LOG_FILE));

	if (empty($log)) {
		$log = [];
	}

	if (isset($log[$_SERVER['REMOTE_ADDR']]) && $log[$_SERVER['REMOTE_ADDR']]['num'] > 3 && time() - $log[$_SERVER['REMOTE_ADDR']]['time'] < 86400) {
		die('This IP address is blocked due to unsuccessful login attempts.');
	}

	foreach ($log as $key => $value) {
		if (time() - $value['time'] > 86400) {
			unset($log[$key]);

			$log_updated = true;
		}
	}

	if (isset($log_updated)) {
		file_put_contents(LOG_FILE, serialize($log));
	}
}

session_set_cookie_params(86400, dirname($_SERVER['REQUEST_URI']));
session_name('pheditor');
session_start();

if (empty(PASSWORD) === false && (isset($_SESSION['pheditor_admin'], $_SESSION['pheditor_password']) === false || $_SESSION['pheditor_admin'] !== true || $_SESSION['pheditor_password'] != PASSWORD)) {
	if (isset($_POST['pheditor_password']) && empty($_POST['pheditor_password']) === false) {
		$password_hash = hash('sha512', $_POST['pheditor_password']);

		if ($password_hash === PASSWORD) {
			session_regenerate_id(true);

			$_SESSION['pheditor_admin'] = true;
			$_SESSION['pheditor_password'] = $password_hash;

			redirect();
		} else {
			$error = 'The entry password is not correct.';

			$log = file_exists(LOG_FILE) ? unserialize(file_get_contents(LOG_FILE)) : array();

			if (isset($log[$_SERVER['REMOTE_ADDR']]) === false) {
				$log[$_SERVER['REMOTE_ADDR']] = array('num' => 0, 'time' => 0);
			}

			$log[$_SERVER['REMOTE_ADDR']]['num'] += 1;
			$log[$_SERVER['REMOTE_ADDR']]['time'] = time();

			file_put_contents(LOG_FILE, serialize($log));
		}
	} else if (isset($_POST['action'])) {
		header('HTTP/1.0 403 Forbidden');

		die('Your session has expired.');
	}

	die('<title>Pheditor</title><form method="post"><div style="text-align:center"><h1><a href="http://github.com/pheditor/pheditor" target="_blank" title="PHP file editor" style="color:#444;text-decoration:none" tabindex="3">Pheditor</a></h1>' . (isset($error) ? '<p style="color:#dd0000">' . $error . '</p>' : null) . '<input id="pheditor_password" name="pheditor_password" type="password" value="" placeholder="Password&hellip;" tabindex="1"><br><br><input type="submit" value="Login" tabindex="2"></div></form><script type="text/javascript">document.getElementById("pheditor_password").focus();</script>');
}

if (isset($_GET['logout'])) {
	if ($_GET['logout'] == $_SESSION['pheditor_token']) {
		unset($_SESSION['pheditor_admin']);

		session_destroy();
	}

	redirect();
}

$permissions = explode(',', PERMISSIONS);
$permissions = array_map('trim', $permissions);
$permissions = array_filter($permissions);

if (count($permissions) < 1) {
	$permissions = explode(',', 'newfile,newdir,editfile,deletefile,deletedir,renamefile,renamedir,changepassword,uploadfile');
}

if (isset($_GET['path'])) {
	header('Content-Type: application/json');

	$dir = realpath(rtrim(MAIN_DIR . DS . trim($_GET['path'], '/'), '/'));

	if ($dir === false || check_path($dir) !== true) {
		die('[]');
	}

	$files = array_slice(scandir($dir), 2);
	$list = [];

	asort($files);

	foreach ($files as $key => $file) {
		if (substr($file, 0, 1) === '.' || (SHOW_PHP_SELF === false && $dir . DS . $file == __FILE__)) {
			continue;
		}

		if (is_dir($dir . DS . $file) && (empty(PATTERN_DIRECTORIES) || preg_match(PATTERN_DIRECTORIES, $file))) {
			$dir_path = str_replace([MAIN_DIR, DS], ['', '/'], $dir . DS . $file . DS);

			$list[] = [
				'text' => $file,
				'icon' => 'far fa-folder',
				'children' => true,
				'a_attr' => [
					'href' => '#' . $dir_path,
					'data-dir' => $dir_path
				],
				'state' => [
					'selected' => false,
				],
			];
		} else if (empty(PATTERN_FILES) || preg_match(PATTERN_FILES, $file)) {
			$file_path = str_replace([MAIN_DIR, DS], ['', '/'], $dir . DS . $file);

			$list[] = [
				'text' => $file,
				'icon' => 'far fa-file',
				'a_attr' => [
					'href' => '#' . $file_path,
					'data-file' => $file_path
				],
				'state' => [
					'selected' => false,
				],
			];
		}
	}

	if (empty($_GET['path'])) {
		$list = [
			'text' => '/',
			'icon' => 'far fa-folder',
			'children' => $list,
			'a_attr' => [
				'href' => '#/',
				'data-dir' => '/',
			],
			'state' => [
				'selected' => false,
			],
		];
	}

	die(json_encode($list, JSON_UNESCAPED_UNICODE));
} else if (isset($_POST['action'])) {
	header('Content-Type: application/json');

	$post_token = $_POST['token'] ?? null;
	$session_token = $_SESSION['pheditor_token'] ?? null;

	if (empty($post_token) || $post_token != $session_token) {
		die(json_error('Invalid token. Please reload the page.'));
	}

	if (isset($_POST['file']) && empty($_POST['file']) === false) {
		$_POST['file'] = urldecode($_POST['file']);

		if (empty(PATTERN_FILES) === false && !preg_match(PATTERN_FILES, basename($_POST['file']))) {
			die(json_error('Invalid file pattern'));
		}
	}

	foreach (['file', 'dir', 'path', 'name', 'destination'] as $value) {
		if (isset($_POST[$value]) && empty($_POST[$value]) === false) {
			$value = urldecode($_POST[$value]);

			if (strpos($value, '../') !== false || strpos($value, '..\\') !== false) {
				die(json_error('Invalid path'));
			}
		}
	}

	switch ($_POST['action']) {
		case 'open':
			$_POST['file'] = urldecode($_POST['file']);

			if (isset($_POST['file']) && file_exists(MAIN_DIR . $_POST['file'])) {
				die(json_success('OK', [
					'data' => file_get_contents(MAIN_DIR . $_POST['file']),
				]));
			}
			break;

		case 'save':
			$file = MAIN_DIR . $_POST['file'];

			if (isset($_POST['file']) && isset($_POST['data']) && (file_exists($file) === false || is_writable($file))) {
				if (file_exists($file) === false) {
					if (in_array('newfile', $permissions) !== true) {
						die(json_error('Permission denied', true));
					}

					file_put_contents($file, $_POST['data']);

					if (function_exists('chmod')) {
						chmod($file, DEFAULT_FILE_PERMISSION);
					}

					echo json_success('File saved successfully');
				} else if (is_writable($file) === false) {
					echo json_error('File is not writable');
				} else {
					if (in_array('editfile', $permissions) !== true) {
						die(json_error('Permission denied'));
					}

					if (file_exists($file)) {
						file_to_history($file);
					}

					file_put_contents($file, $_POST['data']);

					echo json_success('File saved successfully');
				}
			}
			break;

		case 'make-dir':
			if (in_array('newdir', $permissions) !== true) {
				die(json_error('Permission denied'));
			}

			$dir = MAIN_DIR . $_POST['dir'];

			if (file_exists($dir) === false) {
				mkdir($dir, DEFAULT_DIR_PERMISSION);

				if (function_exists('chmod')) {
					chmod($dir, DEFAULT_DIR_PERMISSION);
				}

				echo json_success('Directory created successfully');
			} else {
				echo json_error('Directory already exists');
			}
			break;

		case 'reload':
			echo json_success('OK', [
				'data' => files(MAIN_DIR),
			]);
			break;

		case 'password':
			if (in_array('changepassword', $permissions) !== true) {
				die(json_error('Permission denied'));
			}

			if (isset($_POST['password']) && empty($_POST['password']) === false) {
				$contents = file(__FILE__);
				$password_hash = hash('sha512', $_POST['password']);

				foreach ($contents as $key => $line) {
					if (strpos($line, 'define(\'PASSWORD\'') !== false) {
						$contents[$key] = "define('PASSWORD', '" . $password_hash . "');\n";

						break;
					}
				}

				if (is_writable(__FILE__) === false) {
					die(json_error('File is not writable'));
				}

				file_put_contents(__FILE__, implode($contents));

				$_SESSION['pheditor_password'] = $password_hash;

				session_regenerate_id(true);

				echo json_success('Password changed successfully');
			}
			break;

		case 'delete':
			if (isset($_POST['path']) && file_exists(MAIN_DIR . $_POST['path']) && check_path(MAIN_DIR . $_POST['path'])) {
				$path = MAIN_DIR . $_POST['path'];

				if ($_POST['path'] == '/') {
					echo json_error('Unable to delete main directory');
				} else if (is_dir($path)) {
					if (count(scandir($path)) !== 2) {
						echo json_error('Directory is not empty');
					} else if (is_writable($path) === false) {
						echo json_error('Unable to delete directory');
					} else {
						if (in_array('deletedir', $permissions) !== true) {
							die(json_error('Permission denied'));
						}

						rmdir($path);

						echo json_success('Directory deleted successfully');
					}
				} else {
					if (empty(PATTERN_FILES) === false && !preg_match(PATTERN_FILES, basename($_POST['path']))) {
						die(json_error('Invalid file patterna'));
					}

					file_to_history($path);

					if (is_writable($path)) {
						if (in_array('deletefile', $permissions) !== true) {
							die(json_error('Permission denied'));
						}

						unlink($path);

						echo json_success('File deleted successfully');
					} else {
						echo json_error('Unable to delete file');
					}
				}
			}
			break;

		case 'rename':
			if (isset($_POST['path']) && file_exists(MAIN_DIR . $_POST['path']) && isset($_POST['name']) && empty($_POST['name']) === false) {
				$path = MAIN_DIR . $_POST['path'];
				$new_path = str_replace(basename($path), '', dirname($path)) . DS . $_POST['name'];

				if ($_POST['path'] == '/') {
					echo json_error('Unable to rename main directory');
				} else if (is_dir($path)) {
					if (in_array('renamedir', $permissions) !== true) {
						die(json_error('Permission denied'));
					}

					if (is_writable($path) === false) {
						echo json_error('Unable to rename directory');
					} else {
						rename($path, $new_path);

						echo json_success('Directory renamed successfully');
					}
				} else {
					if (in_array('renamefile', $permissions) !== true) {
						die(json_error('Permission denied'));
					} else if (empty(PATTERN_FILES) === false && !preg_match(PATTERN_FILES, $_POST['name'])) {
						die(json_error('Invalid file pattern: ' . htmlspecialchars($_POST['name'])));
					}

					file_to_history($path);

					if (is_writable($path)) {
						rename($path, $new_path);

						echo json_success('File renamed successfully');
					} else {
						echo json_error('Unable to rename file');
					}
				}
			}
			break;

		case 'upload-file':
			$files = isset($_FILES['uploadfile']) ? $_FILES['uploadfile'] : [];
			$destination = isset($_POST['destination']) ? rtrim($_POST['destination']) : null;

			if (empty($destination) === false && (strpos($destination, '/..') !== false || strpos($destination, '\\..') !== false)) {
				die(json_error('Invalid file destination'));
			}

			$destination = MAIN_DIR . $destination;

			if (file_exists($destination) === false || is_dir($destination) === false) {
				die(json_error('File destination does not exists'));
			}

			if (is_writable($destination) !== true) {
				die(json_error('File destination is not writable'));
			}

			if (is_array($files) && count($files) > 0) {
				for ($i = 0; $i < count($files['name']); $i += 1) {
					if (empty(PATTERN_FILES) === false && !preg_match(PATTERN_FILES, $files['name'][$i])) {
						die(json_error('Invalid file pattern: ' . htmlspecialchars($files['name'][$i])));
					}

					move_uploaded_file($files['tmp_name'][$i], $destination . '/' . $files['name'][$i]);
				}

				echo json_success('File' . (count($files['name']) > 1 ? 's' : null) . ' uploaded successfully');
			}
			break;

		case 'terminal':
			if (in_array('terminal', $permissions) !== false && isset($_POST['command'], $_POST['dir'])) {
				if (function_exists('shell_exec') === false) {
					echo json_error("shell_exec function is disabled\n");

					exit;
				}

				set_time_limit(15);

				$command  = $_POST['command'];
				$dir = $_POST['dir'];

				if (strpos($command, '&') !== false || strpos($command, ';') !== false || strpos($command, '||') !== false) {
					echo json_error("Illegal character(s) in command (& ; ||)\n");

					exit;
				}

				$command_found = false;
				$terminal_commands = explode(',', TERMINAL_COMMANDS);

				foreach ($terminal_commands as $value) {
					$value = trim($value);

					if (strlen($command) >= strlen($value) && substr($command, 0, strlen($value)) == $value) {
						$command_found = true;

						break;
					}
				}

				if ($command_found === false) {
					foreach ($terminal_commands as $key => $value) {
						$commands[$key % 3] = isset($commands[$key % 3]) ? $commands[$key % 3] . "\t" . $value : $value;
					}

					echo json_error("<span class=\"text-danger\">Command not allowed</span>\n<span class=\"text-success\">Available commands:</span>\n" . implode("\n", $commands) . "\n");

					exit;
				}

				$output = shell_exec((empty($dir) ? null : 'cd ' . $dir . ' && ') . $command . ' && echo \ ; pwd');
				$output = trim($output);

				if (empty($output)) {
					$output = null;
					$dir = null;
				} else {
					$output = explode("\n", $output);
					$dir = end($output);

					unset($output[count($output) - 1]);

					$output = implode("\n", $output);
					$output = trim($output) . "\n";
					$output = htmlspecialchars($output);
				}

				echo json_success('OK', ['result' => $output, 'dir' => $dir]);
			}
			break;
	}

	exit;
}

function redirect($address = null)
{
	if (empty($address)) {
		$address = $_SERVER['SCRIPT_NAME'];
	}

	header('Location: ' . $address);
	exit;
}

function file_to_history($file)
{
	if (is_numeric(MAX_HISTORY_FILES) && MAX_HISTORY_FILES > 0) {
		$file_dir = dirname($file);
		$file_name = basename($file);
		$file_history_dir = HISTORY_PATH . str_replace(MAIN_DIR, '', $file_dir);

		foreach ([HISTORY_PATH, $file_history_dir] as $dir) {
			if (file_exists($dir) === false || is_dir($dir) === false) {
				mkdir($dir, 0777, true);
			}
		}

		$history_files = scandir($file_history_d
