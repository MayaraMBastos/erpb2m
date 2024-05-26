<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'erpb2m' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'h6ew@}NlD%hL+SE.7,-,C}3Dq%70KY:6os-wy*0IV/.q[aFdKdT$ +=s#K0xjRpb' );
define( 'SECURE_AUTH_KEY',  'xkCB&$z217U_xj>aM{LzQ_l] -VS*r!4S.)-A,x:6[ZcL#{Mk=bGj=+DH6Y/;yo%' );
define( 'LOGGED_IN_KEY',    'Va9eqGBFOE|A1{RR>,$Uo8=~T#GqrL|Cyg@s`qs*n}+]%G$REU yf$@nbe(L.LpX' );
define( 'NONCE_KEY',        'LA5qpk3e@@8;)67E(=eyfzIJ<wiV}v#t4Qv<*;HG-,qteWha?&h5n34aI 88G>z9' );
define( 'AUTH_SALT',        'dx`g}|B;V?H_PzhnDfFQF-8njU8d t3.dh<.W5-`t.F2V~/Nh`oZ%~GL<%;efR:R' );
define( 'SECURE_AUTH_SALT', '+Y:}SSxF;u$?]Ue%*pU[92PniAxht1~wHyEe/XV-F;1:rx)C1vD6=[JlZPA.knkA' );
define( 'LOGGED_IN_SALT',   'p):dxc3Bijh-cVXYf5N%R^<}%r?4O|;}>I5!RDJc:f?}aFKIb&6}q>w#6a3Y^mIS' );
define( 'NONCE_SALT',       'PV9a}NuO5=(MK0]8SpRs>IobE`GGl=V#lbF{(xa`8X={)Z54ljoYycb(*,w^04<Y' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
