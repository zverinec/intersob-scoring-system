<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Loaders
 */



/**
 * Nette auto loader is responsible for loading Nette classes and interfaces.
 *
 * @author     David Grudl
 * @package Nette\Loaders
 */
class NetteLoader extends AutoLoader
{
	/** @var NetteLoader */
	private static $instance;

	/** @var array */
	public $list = array(
		'AbortException' => '/Application/Exceptions/AbortException',
		'AmbiguousServiceException' => '/Environment/ServiceLocator',
		'Annotation' => '/Reflection/Annotation',
		'Annotations' => '/Reflection/Annotations',
		'AnnotationsParser' => '/Reflection/AnnotationsParser',
		'AppForm' => '/Application/AppForm',
		'Application' => '/Application/Application',
		'ApplicationException' => '/Application/Exceptions/ApplicationException',
		'ArgumentOutOfRangeException' => '/Utils/exceptions',
		'ArrayList' => '/Collections/ArrayList',
		'ArrayTools' => '/Utils/ArrayTools',
		'AuthenticationException' => '/Security/AuthenticationException',
		'AutoLoader' => '/Loaders/AutoLoader',
		'BadRequestException' => '/Application/Exceptions/BadRequestException',
		'BadSignalException' => '/Application/Exceptions/BadSignalException',
		'BaseTemplate' => '/Templates/BaseTemplate',
		'Button' => '/Forms/Controls/Button',
		'Cache' => '/Caching/Cache',
		'CachingHelper' => '/Templates/Filters/CachingHelper',
		'Callback' => '/Utils/Callback',
		'Checkbox' => '/Forms/Controls/Checkbox',
		'ClassReflection' => '/Reflection/ClassReflection',
		'CliRouter' => '/Application/Routers/CliRouter',
		'Collection' => '/Collections/Collection',
		'Component' => '/ComponentModel/Component',
		'ComponentContainer' => '/ComponentModel/ComponentContainer',
		'Config' => '/Config/Config',
		'ConfigAdapterIni' => '/Config/ConfigAdapterIni',
		'Configurator' => '/Environment/Configurator',
		'Control' => '/Application/Control',
		'ConventionalRenderer' => '/Forms/Renderers/ConventionalRenderer',
		'CurlyBracketsFilter' => '/Templates/Filters/LatteFilter',
		'CurlyBracketsMacros' => '/Templates/Filters/LatteFilter',
		'DateTime53' => '/Utils/DateTime',
		'Debug' => '/Debug/Debug',
		'DeprecatedException' => '/Utils/exceptions',
		'DirectoryNotFoundException' => '/Utils/exceptions',
		'DownloadResponse' => '/Application/Responses/DownloadResponse',
		'DummyStorage' => '/Caching/DummyStorage',
		'Environment' => '/Environment/Environment',
		'ExtensionReflection' => '/Reflection/ExtensionReflection',
		'FatalErrorException' => '/Utils/exceptions',
		'FileNotFoundException' => '/Utils/exceptions',
		'FileStorage' => '/Caching/FileStorage',
		'FileUpload' => '/Forms/Controls/FileUpload',
		'ForbiddenRequestException' => '/Application/Exceptions/ForbiddenRequestException',
		'Form' => '/Forms/Form',
		'FormContainer' => '/Forms/FormContainer',
		'FormControl' => '/Forms/Controls/FormControl',
		'FormGroup' => '/Forms/FormGroup',
		'ForwardingResponse' => '/Application/Responses/ForwardingResponse',
		'Framework' => '/Utils/Framework',
		'FreezableObject' => '/Utils/FreezableObject',
		'Ftp' => '/Web/Ftp',
		'FtpException' => '/Web/Ftp',
		'FunctionReflection' => '/Reflection/FunctionReflection',
		'GenericRecursiveIterator' => '/Utils/Iterators/GenericRecursiveIterator',
		'Hashtable' => '/Collections/Hashtable',
		'HiddenField' => '/Forms/Controls/HiddenField',
		'Html' => '/Web/Html',
		'HttpContext' => '/Web/HttpContext',
		'HttpRequest' => '/Web/HttpRequest',
		'HttpResponse' => '/Web/HttpResponse',
		'HttpUploadedFile' => '/Web/HttpUploadedFile',
		'IAnnotation' => '/Reflection/IAnnotation',
		'IAuthenticator' => '/Security/IAuthenticator',
		'IAuthorizator' => '/Security/IAuthorizator',
		'ICacheStorage' => '/Caching/ICacheStorage',
		'ICollection' => '/Collections/ICollection',
		'IComponent' => '/ComponentModel/IComponent',
		'IComponentContainer' => '/ComponentModel/IComponentContainer',
		'IConfigAdapter' => '/Config/IConfigAdapter',
		'IDebuggable' => '/Debug/IDebuggable',
		'IFileTemplate' => '/Templates/IFileTemplate',
		'IFormControl' => '/Forms/IFormControl',
		'IFormRenderer' => '/Forms/IFormRenderer',
		'IHttpRequest' => '/Web/IHttpRequest',
		'IHttpResponse' => '/Web/IHttpResponse',
		'IIdentity' => '/Security/IIdentity',
		'IList' => '/Collections/IList',
		'IMailer' => '/Mail/IMailer',
		'IMap' => '/Collections/IMap',
		'INamingContainer' => '/Forms/INamingContainer',
		'IOException' => '/Utils/exceptions',
		'IPartiallyRenderable' => '/Application/IRenderable',
		'IPermissionAssertion' => '/Security/IPermissionAssertion',
		'IPresenter' => '/Application/IPresenter',
		'IPresenterLoader' => '/Application/IPresenterLoader',
		'IPresenterResponse' => '/Application/IPresenterResponse',
		'IRenderable' => '/Application/IRenderable',
		'IResource' => '/Security/IResource',
		'IRole' => '/Security/IRole',
		'IRouter' => '/Application/IRouter',
		'IServiceLocator' => '/Environment/IServiceLocator',
		'ISet' => '/Collections/ISet',
		'ISignalReceiver' => '/Application/ISignalReceiver',
		'IStatePersistent' => '/Application/IStatePersistent',
		'ISubmitterControl' => '/Forms/ISubmitterControl',
		'ITemplate' => '/Templates/ITemplate',
		'ITranslator' => '/Utils/ITranslator',
		'IUser' => '/Web/IUser',
		'Identity' => '/Security/Identity',
		'Image' => '/Utils/Image',
		'ImageButton' => '/Forms/Controls/ImageButton',
		'ImageMagick' => '/Utils/ImageMagick',
		'InstanceFilterIterator' => '/Utils/Iterators/InstanceFilterIterator',
		'InstantClientScript' => '/Forms/Renderers/InstantClientScript',
		'InvalidLinkException' => '/Application/Exceptions/InvalidLinkException',
		'InvalidPresenterException' => '/Application/Exceptions/InvalidPresenterException',
		'InvalidStateException' => '/Utils/exceptions',
		'JsonResponse' => '/Application/Responses/JsonResponse',
		'KeyNotFoundException' => '/Collections/Hashtable',
		'LatteFilter' => '/Templates/Filters/LatteFilter',
		'LatteMacros' => '/Templates/Filters/LatteMacros',
		'LimitedScope' => '/Loaders/LimitedScope',
		'Link' => '/Application/Link',
		'Mail' => '/Mail/Mail',
		'MailMimePart' => '/Mail/MailMimePart',
		'MemberAccessException' => '/Utils/exceptions',
		'MemcachedStorage' => '/Caching/MemcachedStorage',
		'MethodReflection' => '/Reflection/MethodReflection',
		'MultiRouter' => '/Application/Routers/MultiRouter',
		'MultiSelectBox' => '/Forms/Controls/MultiSelectBox',
		'NCFix' => '/loader',
		'NetteLoader' => '/Loaders/NetteLoader',
		'NotImplementedException' => '/Utils/exceptions',
		'NotSupportedException' => '/Utils/exceptions',
		'Object' => '/Utils/Object',
		'ObjectMixin' => '/Utils/ObjectMixin',
		'Paginator' => '/Utils/Paginator',
		'ParameterReflection' => '/Reflection/ParameterReflection',
		'Permission' => '/Security/Permission',
		'Presenter' => '/Application/Presenter',
		'PresenterComponent' => '/Application/PresenterComponent',
		'PresenterComponentReflection' => '/Application/PresenterComponentReflection',
		'PresenterLoader' => '/Application/PresenterLoader',
		'PresenterRequest' => '/Application/PresenterRequest',
		'PropertyReflection' => '/Reflection/PropertyReflection',
		'RadioList' => '/Forms/Controls/RadioList',
		'RecursiveComponentIterator' => '/ComponentModel/ComponentContainer',
		'RedirectingResponse' => '/Application/Responses/RedirectingResponse',
		'RenderResponse' => '/Application/Responses/RenderResponse',
		'RobotLoader' => '/Loaders/RobotLoader',
		'Route' => '/Application/Routers/Route',
		'Rule' => '/Forms/Rule',
		'Rules' => '/Forms/Rules',
		'SafeStream' => '/Utils/SafeStream',
		'SelectBox' => '/Forms/Controls/SelectBox',
		'SendmailMailer' => '/Mail/SendmailMailer',
		'ServiceLocator' => '/Environment/ServiceLocator',
		'Session' => '/Web/Session',
		'SessionNamespace' => '/Web/SessionNamespace',
		'Set' => '/Collections/Set',
		'SimpleAuthenticator' => '/Security/SimpleAuthenticator',
		'SimpleRouter' => '/Application/Routers/SimpleRouter',
		'SmartCachingIterator' => '/Utils/Iterators/SmartCachingIterator',
		'SnippetHelper' => '/Templates/Filters/SnippetHelper',
		'String' => '/Utils/String',
		'SubmitButton' => '/Forms/Controls/SubmitButton',
		'Template' => '/Templates/Template',
		'TemplateCacheStorage' => '/Templates/TemplateCacheStorage',
		'TemplateFilters' => '/Templates/Filters/TemplateFilters',
		'TemplateHelpers' => '/Templates/Filters/TemplateHelpers',
		'TextArea' => '/Forms/Controls/TextArea',
		'TextBase' => '/Forms/Controls/TextBase',
		'TextInput' => '/Forms/Controls/TextInput',
		'Tools' => '/Utils/Tools',
		'Uri' => '/Web/Uri',
		'UriScript' => '/Web/UriScript',
		'User' => '/Web/User',
	);



	/**
	 * Returns singleton instance with lazy instantiation.
	 * @return NetteLoader
	 */
	public static function getInstance()
	{
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}



	/**
	 * Handles autoloading of classes or interfaces.
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		$type = ltrim($type, '\\');
		if (isset($this->list[$type])) {
			LimitedScope::load(NETTE_DIR . $this->list[$type] . '.php', TRUE);
			self::$count++;

		} elseif (substr($type, 0, 6) === 'Nette\\' && is_file($file = NETTE_DIR . strtr(substr($type, 5), '\\', '/') . '.php')) {
			LimitedScope::load($file, TRUE);
			self::$count++;
		}
	}

}
