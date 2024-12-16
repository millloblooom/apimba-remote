function isCompatible(ua){return!!((function(){'use
strict';return!this&&Function.prototype.bind&&window.JSON;}())&&'querySelector'in document&&'localStorage'in
window&&'addEventListener'in window&&!ua.match(/MSIE 10|NetFront|Opera
Mini|S40OviBrowser|MeeGo|Android.+Glass|^Mozilla\/5\.0 .+
Gecko\/$|googleweblight|PLAYSTATION|PlayStation/));}if(!isCompatible(navigator.userAgent)){document.documentElement.className=document.documentElement.className.replace(/(^|\s)client-js(\s|$)/,'$1client-nojs$2');while(window.NORLQ&&NORLQ[0]){NORLQ.shift()();}NORLQ={push:function(fn){fn();}};RLQ={push:function(){}};}else{if(window.performance&&performance.mark){performance.mark('mwStartup');}(function(){'use
strict';var mw,StringSet,log,hasOwn=Object.hasOwnProperty;function fnv132(str){var hash=0x811C9DC5,i=0;for(;i
<str.length;i++){hash+=(hash<<1)+(hash<<4)+(hash<<7)+(hash<<8)+(hash<<24);hash^=str.charCodeAt(i);}hash=(hash>
    >>0).toString(36).slice(0,5);while(hash.length<5){hash='0'+hash;}return hash;} function
        defineFallbacks(){StringSet=window.Set||function(){var
        set=Object.create(null);return{add:function(value){set[value]=!0;},has:function(value){return value in
        set;}};};}function
        setGlobalMapValue(map,key,value){map.values[key]=value;log.deprecate(window,key,value,map===mw.config&&'Use
        mw.config instead.');}function logError(topic,data){var
        msg,e=data.exception,console=window.console;if(console&&console.log){msg=(e?'Exception':'Error')+'
        in '+data.source+(data.module?' in module '+data.module:'')+(e?'
        :':'.');console.log(msg);if(e&&console.warn){console.warn(e);}}}function
        Map(global){this.values=Object.create(null);if(global===true){this.set=function(selection,value){var
        s;if(arguments.length>1){if(typeof selection==='string'){setGlobalMapValue(this,selection,value);return
        true;}}else if(typeof selection==='object'){for(s in selection){setGlobalMapValue(this,s,selection[s]);}return
        true;}return false;};}}Map.prototype={constructor:Map,get:function(selection,fallback){var
        results,i;fallback=arguments.length>1?fallback:null;if(Array.isArray(selection)){results={};for(i=0;i
        <selection.length;i++){if(typeof selection[i]==='string' ){results[selection[i]]=selection[i]in
            this.values?this.values[selection[i]]:fallback;}}return results;}if(typeof selection==='string' ){return
            selection in this.values?this.values[selection]:fallback;}if(selection===undefined){results={};for(i in
            this.values){results[i]=this.values[i];}return results;}return fallback;},set:function(selection,value){var
            s;if(arguments.length>1){if(typeof selection==='string'){this.values[selection]=value;return true;}}else
            if(typeof selection==='object'){for(s in selection){this.values[s]=selection[s];}return true;}return
            false;},exists:function(selection){var i;if(Array.isArray(selection)){for(i=0;i
            <selection.length;i++){if(typeof selection[i]!=='string' ||!(selection[i]in this.values)){return
                false;}}return true;}return typeof selection==='string' &&selection in
                this.values;}};defineFallbacks();log=(function(){var
                log=function(){},console=window.console;log.warn=console&&console.warn?Function.prototype.bind.call(console.warn,console):function(){};log.error=console&&console.error?Function.prototype.bind.call(console.error,console):function(){};log.deprecate=function(obj,key,val,msg,logName){var
                stacks;function maybeLog(){var name=logName||key,trace=new Error().stack;if(!stacks){stacks=new
                StringSet();}if(!stacks.has(trace)){stacks.add(trace);if(logName||obj===window){mw.track('mw.deprecate',name);}mw.log.warn('Use
                of "'+name+'" is
                deprecated.'+(msg?' '+msg:''));}}try{Object.defineProperty(obj,key,{configurable:!0,enumerable:!0,get:function(){maybeLog();return val;},set:function(newVal){maybeLog();val=newVal;}});}catch(err){obj[key]=val;}};return log;}());mw={redefineFallbacksForTest:window.QUnit&&defineFallbacks,now:function(){var perf=window.performance,navStart=perf&&perf.timing&&perf.timing.navigationStart;mw.now=navStart&&perf.now?function(){return navStart+perf.now();}:
Date.now;return mw.now();},trackQueue:[],track:function(topic,data){mw.trackQueue.push({topic:topic,timeStamp:mw.now(),data:data});},trackError:function(topic,data){mw.track(topic,data);logError(topic,data);},Map:Map,config:new Map(true),messages:new Map(),templates:new Map(),log:log,loader:(function(){var registry=Object.create(null),sources=Object.create(null),handlingPendingRequests=!1,pendingRequests=[],queue=[],jobs=[],willPropagate=!1,errorModules=[],baseModules=["jquery","mediawiki.base"],marker=document.querySelector('
                meta[name="ResourceLoaderDynamicStyles"
                ]'),nextCssBuffer,rAF=window.requestAnimationFrame||setTimeout;function newStyleTag(text,nextNode){var
                el=document.createElement('style');el.appendChild(document.createTextNode(text));if(nextNode&&nextNode.parentNode){nextNode.parentNode.insertBefore(el,nextNode);}else{document.head.appendChild(el);}return
                el;}function flushCssBuffer(cssBuffer){var
                i;cssBuffer.active=!1;newStyleTag(cssBuffer.cssText,marker);for(i=0;i
                <cssBuffer.callbacks.length;i++){cssBuffer.callbacks[i]();}}function
                addEmbeddedCSS(cssText,callback){if(!nextCssBuffer||nextCssBuffer.active===false||cssText.slice(0,'@import'.length)==='@import'
                ){nextCssBuffer={cssText:'',callbacks:[],active:null};}nextCssBuffer.cssText+='\n'
                +cssText;nextCssBuffer.callbacks.push(callback);if(nextCssBuffer.active===null){nextCssBuffer.active=!0;rAF(flushCssBuffer.bind(null,nextCssBuffer));}}function
                getCombinedVersion(modules){var hashes=modules.reduce(function(result,module){return
                result+registry[module].version;},'');return fnv132(hashes);}function allReady(modules){var
                i=0;for(;i<modules.length;i++){if(mw.loader.getState(modules[i])!=='ready' ){return false;}}return
                true;}function allWithImplicitReady(module){return
                allReady(registry[module].dependencies)&&(baseModules.indexOf(module)!==-1||allReady(baseModules));}function
                anyFailed(modules){var
                state,i=0;for(;i<modules.length;i++){state=mw.loader.getState(modules[i]);if(state==='error'
                ||state==='missing' ){return true;}}return false;}function doPropagation(){var
                errorModule,baseModuleError,module,i,failed,job,didPropagate=!0;do{didPropagate=!1;while(errorModules.length){errorModule=errorModules.shift();baseModuleError=baseModules.indexOf(errorModule)!==-1;for(module
                in registry){if(registry[module].state!=='error' &&registry[module].state!=='missing'
                ){if(baseModuleError&&baseModules.indexOf(module)===-1){registry[module].state='error'
                ;didPropagate=!0;}else
                if(registry[module].dependencies.indexOf(errorModule)!==-1){registry[module].state='error'
                ;errorModules.push(module);didPropagate=!0;}}}}for(module in
                registry){if(registry[module].state==='loaded'
                &&allWithImplicitReady(module)){execute(module);didPropagate=!0;}}for(i=0;i<jobs.length;i++){job=jobs[i];failed=anyFailed(job.dependencies);if(failed||allReady(job.dependencies)){jobs.splice(i,1);i-=1;try{if(failed&&job.error){job.error(new
                Error('Failed dependencies'),job.dependencies);}else if(!failed&&job.ready){job.
                ready();}}catch(e){mw.trackError('resourceloader.exception',{exception:e,source:'load-callback'});}didPropagate=!0;}}}while(didPropagate);willPropagate=!1;}function
                requestPropagation(){if(willPropagate){return;}willPropagate=!0;mw.requestIdleCallback(doPropagation,{timeout:1});}function
                setAndPropagate(module,state){registry[module].state=state;if(state==='loaded' ||state==='ready'
                ||state==='error' ||state==='missing' ){if(state==='ready' ){mw.loader.store.add(module);}else
                if(state==='error' ||state==='missing' ){errorModules.push(module);}requestPropagation();}}function
                sortDependencies(module,resolved,unresolved){var i,skip,deps;if(!(module in registry)){throw new
                Error('Unknown module: '+module);}if(typeof registry[module].skip===' string'){skip=(new
                Function(registry[module].skip)());registry[module].skip=!!skip;if(skip){registry[module].dependencies=[];setAndPropagate(module,'ready');return;}}if(!unresolved){unresolved=new
                StringSet();}deps=registry[module].dependencies;
                unresolved.add(module);for(i=0;i<deps.length;i++){if(resolved.indexOf(deps[i])===-1){if(unresolved.has(deps[i])){throw
                new Error('Circular reference detected: '+module+' ->
                '+deps[i]);}sortDependencies(deps[i],resolved,unresolved);}}resolved.push(module);}function
                resolve(modules){var resolved=baseModules.slice(),i=0;for(;i
                <modules.length;i++){sortDependencies(modules[i],resolved);}return resolved;}function
                    resolveStubbornly(modules){var
                    saved,resolved=baseModules.slice(),i=0;for(;i<modules.length;i++){saved=resolved.slice();try{sortDependencies(modules[i],resolved);}catch(err){resolved=saved;mw.log.warn('Skipped
                    unresolvable module '+modules[i]);if(modules[i]in registry){mw.trackError('
                    resourceloader.exception',{exception:err,source:'resolve'});}}}return resolved;}function
                    resolveRelativePath(relativePath,basePath){var
                    prefixes,prefix,baseDirParts,relParts=relativePath.match(/^((?:\.\.?\/)+)(.*)$/);if(!relParts){return
                    null;}baseDirParts=basePath.split('/');baseDirParts.pop();prefixes=relParts[1].split('/');prefixes.pop();while((prefix=prefixes.pop())!==undefined){if(prefix==='..'
                    ){baseDirParts.pop();}}return(baseDirParts.length?baseDirParts.join('/')+'/':'')+relParts[2];}function
                    makeRequireFunction(moduleObj,basePath){return function require(moduleName){var
                    fileName,fileContent,result,moduleParam,scriptFiles=moduleObj.script.files;fileName=resolveRelativePath(moduleName,basePath);if(fileName===null){return
                    mw.loader.require(moduleName);}if(!hasOwn.call(scriptFiles,fileName)){throw new Error('Cannot
                    require undefined
                    file '+fileName);}if(hasOwn.call(moduleObj.packageExports,fileName)){return moduleObj.packageExports[fileName];}fileContent=scriptFiles[fileName];if(typeof fileContent==='
                    function'){moduleParam={exports:{}};fileContent(makeRequireFunction(moduleObj,fileName),moduleParam);result=moduleParam.exports;}else{result=fileContent;}moduleObj.packageExports[fileName]=result;return
                    result;};}function addScript(src,callback){var script=document.createElement( 'script'
                    );script.src=src;script.onload=script.onerror=function(){if(script.parentNode){script.parentNode.removeChild(script);}if(callback){callback();callback=null;}};document.head.appendChild(script);}function
                    queueModuleScript(src,moduleName,callback){pendingRequests.push(function(){if(moduleName!=='jquery'
                    ){window.require=mw.loader.require;window.module=registry[moduleName].module;}addScript(src,function(){delete
                    window.module;callback();if(pendingRequests[0]){pendingRequests.shift()();}else{handlingPendingRequests=!1;}});});if(!handlingPendingRequests&&pendingRequests[0]){handlingPendingRequests=!0;pendingRequests.shift()();}}function
                    addLink(url,media,nextNode){var el=document.createElement('link');el.rel='stylesheet'
                    ;if(media&&media!=='all'
                    ){el.media=media;}el.href=url;if(nextNode&&nextNode.parentNode){nextNode.parentNode.insertBefore(el,nextNode);}else{document.head.appendChild(el);}}function
                    domEval(code){var script=document.createElement('script');if(mw.config.get( 'wgCSPNonce'
                    )!==false){script.nonce=mw.config.get('wgCSPNonce');}script.text=code;document.head.appendChild(script);script.parentNode.removeChild(script);}function
                    enqueue(dependencies,ready,error){if(allReady(dependencies)){if(ready!==undefined){ready();}return;}if(anyFailed(dependencies)){if(error!==undefined){error(new
                    Error('One or more dependencies failed to
                    load'),dependencies);}return;}if(ready!==undefined||error!==undefined){jobs.push({dependencies:dependencies.filter(function(module){var
                    state=registry[module].state;return state==='registered' ||state==='loaded' ||state==='loading'
                    ||state==='executing'
                    ;}),ready:ready,error:error});}dependencies.forEach(function(module){if(registry[module].state==='registered'
                    &&queue.indexOf(module)===-1){queue.push(module);}});mw.loader.work();}function execute(module){var
                    key,value,media,i,urls,cssHandle,siteDeps,siteDepErr,runScript,cssPending=0;if(registry[module].state!=='loaded'
                    ){throw new Error('Module in state "'+registry[module].state+
'" may not execute: '+module);}registry[module].state=' executing';runScript=function(){var
                    script,markModuleReady,nestedAddScript,mainScript;script=registry[module].script;markModuleReady=function(){setAndPropagate(module,'ready');};nestedAddScript=function(arr,callback,i){if(i>
                    =arr.length){callback();return;}queueModuleScript(arr[i],module,function(){nestedAddScript(arr,callback,i+1);});};try{if(Array.isArray(script)){nestedAddScript(script,markModuleReady,0);}else
                    if(typeof script==='function'||(typeof script==='object'&&script!==null)){if(typeof
                    script==='function'){if(module==='jquery'){script();}else{script(window.$,window.$,mw.loader.require,registry[module].module);}}else{mainScript=script.files[script.main];if(typeof
                    mainScript!=='function'){throw new Error('Main file in module '+module+' must be a
                    function');}mainScript(makeRequireFunction(registry[module],script.main),registry[module].module);}markModuleReady();}else
                    if(typeof script==='string'){domEval(script);
                    markModuleReady();}else{markModuleReady();}}catch(e){setAndPropagate(module,'error');mw.trackError('resourceloader.exception',{exception:e,module:module,source:'module-execute'});}};if(registry[module].messages){mw.messages.set(registry[module].messages);}if(registry[module].templates){mw.templates.set(module,registry[module].templates);}cssHandle=function(){cssPending++;return
                    function(){var
                    runScriptCopy;cssPending--;if(cssPending===0){runScriptCopy=runScript;runScript=undefined;runScriptCopy();}};};if(registry[module].style){for(key
                    in
                    registry[module].style){value=registry[module].style[key];media=undefined;if(key!=='url'&&key!=='css'){if(typeof
                    value==='string'){addEmbeddedCSS(value,cssHandle());}else{media=key;key='bc-url';}}if(Array.isArray(value)){for(i=0;i<value.length;i++){if(key==='bc-url'){addLink(value[i],media,marker);}else
                        if(key==='css' ){addEmbeddedCSS(value[i],cssHandle());}}}else if(typeof value==='object'
                        ){for(media in value){urls=value[media];for(i=0;i<urls.length;i++
                        ){addLink(urls[i],media,marker);}}}}}if(module==='user'
                        ){try{siteDeps=resolve(['site']);}catch(e){siteDepErr=e;runScript();}if(siteDepErr===undefined){enqueue(siteDeps,runScript,runScript);}}else
                        if(cssPending===0){runScript();}}function sortQuery(o){var key,sorted={},a=[];for(key in
                        o){a.push(key);}a.sort();for(key=0;key<a.length;key++){sorted[a[key]]=o[a[key]];}return
                        sorted;}function buildModulesString(moduleMap){var p,prefix,str=[],list=[];function
                        restore(suffix){return p+suffix;}for(prefix in moduleMap){p=prefix===''
                        ?'':prefix+'.';str.push(p+moduleMap[prefix].join(','));list.push.apply(list,moduleMap[prefix].map(restore));}return{str:str.join('|'),list:list};}function
                        resolveIndexedDependencies(modules){var i,j,deps;function resolveIndex(dep){return typeof
                        dep==='number'
                        ?modules[dep][0]:dep;}for(i=0;i<modules.length;i++){deps=modules[i][2];if(deps){for(j=0;j<deps.length;j++){deps[j]=resolveIndex(deps[j]);}}}}function
                        makeQueryString(params){return Object.keys(params).map(function (key){return
                        encodeURIComponent(key)+'='+encodeURIComponent(params[key]);}).join(' &');}function
                        batchRequest(batch){var
                        reqBase,splits,b,bSource,bGroup,source,group,i,modules,sourceLoadScript,currReqBase,currReqBaseLength,moduleMap,currReqModules,l,lastDotIndex,prefix,suffix,bytesAdded;function
                        doRequest(){var
                        query=Object.create(currReqBase),packed=buildModulesString(moduleMap);query.modules=packed.str;query.version=getCombinedVersion(packed.list);query=sortQuery(query);addScript(sourceLoadScript+'?'+makeQueryString(query));}if(!batch.length){return;}batch.sort();reqBase={"lang":"en","skin":"vector"};splits=Object.create(null);for(b=0;b<batch.length;b++){bSource=registry[batch[b]].source;bGroup=registry[batch[b]].group;if(!splits[bSource]){splits[bSource]=Object.create(null);}if(!splits[bSource][bGroup]){splits[bSource][bGroup]=[];}splits[bSource][bGroup].push(batch[b]);}for(source
                        in splits){sourceLoadScript=sources[source];for(group in
                        splits[source]){modules=splits[source][group];
                        currReqBase=Object.create(reqBase);if(group===0&&mw.config.get('wgUserName')!==null){currReqBase.user=mw.config.get('wgUserName');}currReqBaseLength=makeQueryString(currReqBase).length+23;l=currReqBaseLength;moduleMap=Object.create(null);currReqModules=[];for(i=0;i<modules.length;i++){lastDotIndex=modules[i].lastIndexOf('.');prefix=modules[i].substr(0,lastDotIndex);suffix=modules[i].slice(lastDotIndex+1);bytesAdded=moduleMap[prefix]?suffix.length+3:modules[i].length+3;if(currReqModules.length&&l+bytesAdded>mw.loader.maxQueryLength){doRequest();l=currReqBaseLength;moduleMap=Object.create(null);currReqModules=[];mw.track('resourceloader.splitRequest',{maxQueryLength:mw.loader.maxQueryLength});}if(!moduleMap[prefix]){moduleMap[prefix]=[];}l+=bytesAdded;moduleMap[prefix].push(suffix);currReqModules.push(modules[i]);}if(currReqModules.length){doRequest();}}}}function
                        asyncEval(implementations,cb){if(!implementations.length){return;}mw.requestIdleCallback(function(){try{domEval(
                        implementations.join(';'));}catch(err){cb(err);}});}function getModuleKey(module){return module
                        in registry?(module+'@'+registry[module].version):null;}function splitModuleKey(key){var
                        index=key.indexOf('@');if(index===-1){return{name:key,version:''};}return{name:key.slice(0,index),version:key.slice(index+1)};}function
                        registerOne(module,version,dependencies,group,source,skip){if(module in registry){throw new
                        Error('module already registered:
                        '+module);}registry[module]={module:{exports:{}},packageExports:{},version:String(version||''),dependencies:dependencies||[],group:typeof
                        group==='undefined'?null:group,source:typeof
                        source==='string'?source:'local',state:'registered',skip:typeof
                        skip==='string'?skip:null};}return{moduleRegistry:registry,maxQueryLength:2000,addStyleTag:newStyleTag,enqueue:enqueue,resolve:resolve,work:function(){var
                        implementations,sourceModules,batch=[],q=0;for(;q<queue.length;q++){if(queue[q]in
                            registry&&registry[queue[q]].state==='registered' ){if(batch.indexOf(
                            queue[q])===-1){batch.push(queue[q]);registry[queue[q]].state='loading'
                            ;}}}queue=[];if(!batch.length){return;}mw.loader.store.init();if(mw.loader.store.enabled){implementations=[];sourceModules=[];batch=batch.filter(function(module){var
                            implementation=mw.loader.store.get(module);if(implementation){implementations.push(implementation);sourceModules.push(module);return
                            false;}return true;});asyncEval(implementations,function(err){var
                            failed;mw.loader.store.stats.failed++;mw.loader.store.clear();mw.trackError('resourceloader.exception',{exception:err,source:'store-eval'});failed=sourceModules.filter(function(module){return
                            registry[module].state==='loading'
                            ;});batchRequest(failed);});}batchRequest(batch);},addSource:function(ids){var id;for(id in
                            ids){if(id in sources){throw new Error('source already
                            registered: '+id);}sources[id]=ids[id];}},register:function(modules){var i;if(typeof modules==='
                            object'){resolveIndexedDependencies(modules);for(i=0;i<modules.length;i++){registerOne.apply(
                            null,modules[i]);}}else{registerOne.apply(null,arguments);}},implement:function(module,script,style,messages,templates){var
                            split=splitModuleKey(module),name=split.name,version=split.version;if(!(name in
                            registry)){mw.loader.register(name);}if(registry[name].script!==undefined){throw new
                            Error('module already
                            implemented: '+name);}if(version){registry[name].version=version;}registry[name].script=script||null;registry[name].style=style||null;registry[name].messages=messages||null;registry[name].templates=templates||null;if(registry[name].state!=='
                            error'&&registry[name].state!=='missing'
                            ){setAndPropagate(name,'loaded');}},load:function(modules,type){if(typeof modules==='string'
                            &&/^(https?:)?\/?\//.test(modules)){if(type==='text/css' ){addLink(modules);}else
                            if(type==='text/javascript' ||type===undefined){addScript(modules);}else{throw new
                            Error('Invalid type '+type);}}else{modules=typeof modules==='
                            string'?[modules]:modules;enqueue(resolveStubbornly(modules),undefined,undefined);}},state:
                            function(states){var module,state;for(module in states){state=states[module];if(!(module in
                            registry)){mw.loader.register(module);}setAndPropagate(module,state);}},getVersion:function(module){return
                            module in registry?registry[module].version:null;},getState:function(module){return module
                            in registry?registry[module].state:null;},getModuleNames:function(){return
                            Object.keys(registry);},require:function(moduleName){var
                            state=mw.loader.getState(moduleName);if(state!=='ready' ){throw new
                            Error('Module "'+moduleName+'" is not loaded');}return
                            registry[moduleName].module.exports;},store:{enabled:null,MODULE_SIZE_MAX:1e5,items:{},queue:[],stats:{hits:0,misses:0,expired:0,failed:0},toJSON:function(){return{items:mw.loader.store.items,vary:mw.loader.store.vary,asOf:Math.ceil(Date.now()/1e7)};},key:"MediaWikiModuleStore:apimba",vary:"vector:1:en",init:function(){var
                            raw,data;if(this.enabled!==null){return;}if(!true||/Firefox/.test(navigator.userAgent)){this.clear();this.enabled=!1;return;}try
                            {raw=localStorage.getItem(this.key);this.enabled=!0;data=JSON.parse(raw);if(data&&typeof
                            data.items==='object'
                            &&data.vary===this.vary&&Date.now()<(data.asOf*1e7)+259e7){this.items=data.items;return;}}catch(e){}if(raw===undefined){this.enabled=!1;}},get:function(module){var
                            key;if(!this.enabled){return false;}key=getModuleKey(module);if(key in
                            this.items){this.stats.hits++;return this.items[key];}this.stats.misses++;return
                            false;},add:function(module){if(!this.enabled){return;}this.queue.push(module);this.requestUpdate();},set:function(module){var
                            key,args,src,encodedScript,descriptor=mw.loader.moduleRegistry[module];key=getModuleKey(module);if(key
                            in this.items||!descriptor||descriptor.state!=='ready'
                            ||!descriptor.version||descriptor.group===1||descriptor.group===0||[descriptor.script,descriptor.style,descriptor.messages,descriptor.templates].indexOf(undefined)!==-1){return;}try{if(typeof
                            descriptor.script==='function' ){encodedScript=String(descriptor.script);}else if(typeof
                            descriptor.script==='object'
                            &&descriptor.script&&!Array.isArray(descriptor.script)){encodedScript='{'
                            +'main:'+JSON.stringify(descriptor.script.main)+','+'files:{'+Object.keys(descriptor.script.files).map(function(key){var
                            value=descriptor.script.files[key];return JSON.stringify(key)+':'+(typeof value==='function'
                            ?value:JSON.stringify(value));}).join(',')+'}}';}else{encodedScript=JSON.stringify(descriptor.script);}args=[JSON.stringify(key),encodedScript,JSON.stringify(descriptor.style),JSON.stringify(descriptor.messages),JSON.stringify(descriptor.templates)];}catch(e){mw.trackError('resourceloader.exception',{exception:e,source:'store-localstorage-json'});return;}src='mw.loader.implement('
                            +args.join(',')+');';if(src.length>
                            this.MODULE_SIZE_MAX){return;}this.items[key]=src;},prune:function(){var key,module;for(key
                            in
                            this.items){module=key.slice(0,key.indexOf('@'));if(getModuleKey(module)!==key){this.stats.expired++;delete
                            this.items[key];}else if(this.items[key].length>this.
                            MODULE_SIZE_MAX){delete
                            this.items[key];}}},clear:function(){this.items={};try{localStorage.removeItem(this.key);}catch(e){}},requestUpdate:(function(){var
                            hasPendingWrites=!1;function flushWrites(){var
                            data,key;mw.loader.store.prune();while(mw.loader.store.queue.length){mw.loader.store.set(mw.loader.store.queue.shift());}key=mw.loader.store.key;try{localStorage.removeItem(key);data=JSON.stringify(mw.loader.store);localStorage.setItem(key,data);}catch(e){mw.trackError('resourceloader.exception',{exception:e,source:'store-localstorage-update'});}hasPendingWrites=!1;}function
                            onTimeout(){mw.requestIdleCallback(flushWrites);}return
                            function(){if(!hasPendingWrites){hasPendingWrites=!0;setTimeout(onTimeout,2000);}};}())}};}())};window.mw=window.mediaWiki=mw;}());mw.requestIdleCallbackInternal=function(callback){setTimeout(function(){var
                            start=mw.now();callback({didTimeout:!1,timeRemaining:function(){return
                            Math.max(0,50-(mw.now()-start));}});},1);};mw.requestIdleCallback=window.
                            requestIdleCallback?window.requestIdleCallback.bind(window):mw.requestIdleCallbackInternal;(function(){mw.loader.addSource({"local":"/wiki/load.php"});mw.loader.register([["site","1iwgg",[1]],["site.styles","mc0ao",[],2],["noscript","r22l1",[],3],["filepage","1yjvh"],["user","k1cuu",[],0],["user.styles","8fimp",[],0],["user.defaults","1akwj"],["user.options","r5ung",[6],1],["user.tokens","tffin",[],1],["mediawiki.skinning.elements","1kbrd"],["mediawiki.skinning.content","bq5my"],["mediawiki.skinning.interface","zsz1m"],["jquery.makeCollapsible.styles","1v5kk"],["mediawiki.skinning.content.parsoid","sml64"],["mediawiki.skinning.content.externallinks","1nwaz"],["jquery","ew64a"],["mediawiki.base","1pnek",[15]],["jquery.chosen","xgr27"],["jquery.client","1h8jy"],["jquery.color","1at2m"],["jquery.confirmable","1vu00",[144]],["jquery.cookie","xnx1h"],["jquery.form","1v1s4"],["jquery.fullscreen","1ssyz"],["jquery.getAttrs","1gy7s"],["jquery.highlightText","l0wmv",[116]],[
                            "jquery.hoverIntent","1s1oe"],["jquery.i18n","1cj08",[143]],["jquery.lengthLimit","9h0n9",[99]],["jquery.makeCollapsible","1rse0",[12]],["jquery.mw-jump","1qc46"],["jquery.spinner","1gqjz"],["jquery.jStorage","19ixt"],["jquery.suggestions","yjl0z",[25]],["jquery.tabIndex","12nrh"],["jquery.tablesorter","1r12e",[36,145,116]],["jquery.tablesorter.styles","1y16o"],["jquery.textSelection","162gq",[18]],["jquery.throttle-debounce","11r63"],["jquery.tipsy","134fl"],["jquery.ui","dpiik"],["jquery.ui.core","1lz57",[40]],["jquery.ui.core.styles","3m146",[40]],["jquery.ui.accordion","1bzgr",[40]],["jquery.ui.autocomplete","3wou0",[40]],["jquery.ui.button","hp2mx",[40]],["jquery.ui.datepicker","1foo5",[40]],["jquery.ui.dialog","1l8vr",[40]],["jquery.ui.draggable","3m146",[40]],["jquery.ui.droppable","3m146",[40]],["jquery.ui.menu","1tvx1",[40]],["jquery.ui.mouse","3m146",[40]],["jquery.ui.position","3m146",[40]],["jquery.ui.progressbar","1vwfk",[40]],["jquery.ui.resizable","1muzn",[40]],[
                            "jquery.ui.selectable","af3xm",[40]],["jquery.ui.slider","1ntzc",[40]],["jquery.ui.sortable","3m146",[40]],["jquery.ui.tabs","aii26",[40]],["jquery.ui.tooltip","e6mxl",[40]],["jquery.ui.widget","3m146",[40]],["jquery.effects.core","3m146",[40]],["jquery.effects.blind","3m146",[40]],["jquery.effects.clip","3m146",[40]],["jquery.effects.drop","3m146",[40]],["jquery.effects.highlight","3m146",[40]],["jquery.effects.scale","3m146",[40]],["jquery.effects.shake","3m146",[40]],["moment","1be76",[141,116]],["mediawiki.template","12trs"],["mediawiki.template.mustache","sze5q",[69]],["mediawiki.template.regexp","b2zch",[69]],["mediawiki.apipretty","2v5wv"],["mediawiki.api","1wc52",[104,8]],["mediawiki.content.json","ee4g7"],["mediawiki.confirmCloseWindow","rsu1f"],["mediawiki.debug","1ama8",[235]],["mediawiki.diff.styles","3wa8p"],["mediawiki.feedback","ehjsr",[92,243]],["mediawiki.feedlink","mdmuu"],["mediawiki.filewarning","znkeo",[235,247]],["mediawiki.ForeignApi","11qqf",[82]],[
                            "mediawiki.ForeignApi.core","1ix5c",[113,73,231]],["mediawiki.helplink","h7si1"],["mediawiki.hlist","473eg"],["mediawiki.htmlform","zw5lw",[28,116]],["mediawiki.htmlform.checker","1lzye",[116]],["mediawiki.htmlform.ooui","1ua13",[235]],["mediawiki.htmlform.styles","1o0g7"],["mediawiki.htmlform.ooui.styles","1sgag"],["mediawiki.icon","1qz49"],["mediawiki.inspect","f67tl",[99,116]],["mediawiki.messagePoster","qo8h4",[81]],["mediawiki.messagePoster.wikitext","11c79",[92]],["mediawiki.notification","1an86",[116,123]],["mediawiki.notify","1almh"],["mediawiki.notification.convertmessagebox","1xelg",[94]],["mediawiki.notification.convertmessagebox.styles","1n39f"],["mediawiki.RegExp","3m146",[116]],["mediawiki.String","1cs4l"],["mediawiki.pager.tablePager","ulg3n"],["mediawiki.pulsatingdot","10y7l"],["mediawiki.searchSuggest","1ubbh",[24,33,73,7]],["mediawiki.storage","193jh"],["mediawiki.Title","fly88",[99,116]],["mediawiki.Upload","1riai",[73]],["mediawiki.ForeignUpload","cgzcf",[81,105]],[
                            "mediawiki.ForeignStructuredUpload","13udi",[106]],["mediawiki.Upload.Dialog","1e4wh",[109]],["mediawiki.Upload.BookletLayout","1tlct",[105,144,114,227,68,238,243,248,249]],["mediawiki.ForeignStructuredUpload.BookletLayout","vc8fa",[107,109,148,214,208]],["mediawiki.toc","igp1i",[120]],["mediawiki.toc.styles","1t45f"],["mediawiki.Uri","gtu2l",[116,71]],["mediawiki.user","1nmaf",[73,103,7]],["mediawiki.userSuggest","1rhg2",[33,73]],["mediawiki.util","14n22",[18]],["mediawiki.viewport","m0hfv"],["mediawiki.checkboxtoggle","1erf8"],["mediawiki.checkboxtoggle.styles","9ypfw"],["mediawiki.cookie","u8ja5",[21]],["mediawiki.experiments","1rc9d"],["mediawiki.editfont.styles","ljhab"],["mediawiki.visibleTimeout","1n5gh"],["mediawiki.action.delete","s9p3k",[28,235]],["mediawiki.action.delete.file","17mef",[28,235]],["mediawiki.action.edit","1p543",[37,127,73,122,210]],["mediawiki.action.edit.styles","5zxyd"],["mediawiki.action.edit.collapsibleFooter","uum1x",[29,90,103]],[
                            "mediawiki.action.edit.preview","4mqcr",[31,37,73,77,144,235]],["mediawiki.action.history","ms5jh",[29]],["mediawiki.action.history.styles","fljzj"],["mediawiki.action.view.dblClickEdit","yff6k",[116,7]],["mediawiki.action.view.metadata","omm94",[140]],["mediawiki.action.view.categoryPage.styles","11c0i"],["mediawiki.action.view.postEdit","f5jf4",[144,94]],["mediawiki.action.view.redirect","1cpfp",[18]],["mediawiki.action.view.redirectPage","5y447"],["mediawiki.action.view.rightClickEdit","17y9v"],["mediawiki.action.edit.editWarning","1ftqh",[37,75,144]],["mediawiki.action.view.filepage","1wnhc"],["mediawiki.language","1aqo9",[142]],["mediawiki.cldr","s8f8y",[143]],["mediawiki.libs.pluralruleparser","3ngz2"],["mediawiki.jqueryMsg","1behw",[141,116,7]],["mediawiki.language.months","1t4rt",[141]],["mediawiki.language.names","5klas",[141]],["mediawiki.language.specialCharacters","1ytgm",[141]],["mediawiki.libs.jpegmeta","1l8sx"],["mediawiki.page.gallery","7rcqi",[38,150]],[
                            "mediawiki.page.gallery.styles","1787w"],["mediawiki.page.gallery.slideshow","11k9f",[73,238,257,259]],["mediawiki.page.ready","1vmhp",[73,95]],["mediawiki.page.startup","1r3kg"],["mediawiki.page.patrol.ajax","1gfzc",[31,73,95]],["mediawiki.page.watch.ajax","jzrwc",[73,144,95]],["mediawiki.page.rollback.confirmation","1xpno",[20]],["mediawiki.page.image.pagination","19yi3",[31,116]],["mediawiki.rcfilters.filters.base.styles","9v2yf"],["mediawiki.rcfilters.highlightCircles.seenunseen.styles","1ih6d"],["mediawiki.rcfilters.filters.dm","wlnz2",[113,144,114,231]],["mediawiki.rcfilters.filters.ui","mr4wz",[29,160,205,244,251,253,254,255,257,258]],["mediawiki.interface.helpers.styles","1qtf2"],["mediawiki.special","1ftch"],["mediawiki.special.apisandbox","19xju",[29,144,205,211,234,249,254]],["mediawiki.special.block","1ah1j",[85,208,222,215,223,220,249,251]],["mediawiki.misc-authed-ooui","10342",[87,205,210]],["mediawiki.special.changeslist","4zl6u"],[
                            "mediawiki.special.changeslist.enhanced","1qdta"],["mediawiki.special.changeslist.legend","g38yh"],["mediawiki.special.changeslist.legend.js","17rby",[29,120]],["mediawiki.special.contributions","x3e40",[29,144,208,234]],["mediawiki.special.edittags","1p0so",[17,28]],["mediawiki.special.import","1c26y"],["mediawiki.special.preferences.ooui","pt7x5",[75,122,96,103,215]],["mediawiki.special.preferences.styles.ooui","1og4i"],["mediawiki.special.recentchanges","kkheg",[205]],["mediawiki.special.revisionDelete","5h610",[28]],["mediawiki.special.search","bflso",[225]],["mediawiki.special.search.commonsInterwikiWidget","dmyn4",[113,73,144]],["mediawiki.special.search.interwikiwidget.styles","83zcd"],["mediawiki.special.search.styles","f9za3"],["mediawiki.special.undelete","1c4om",[205,210]],["mediawiki.special.unwatchedPages","iawjo",[73,95]],["mediawiki.special.upload","380w0",[31,73,75,144,148,163,69]],["mediawiki.special.userlogin.common.styles","cwgmg"],[
                            "mediawiki.special.userlogin.login.styles","1k1u5"],["mediawiki.special.userlogin.signup.js","wdifb",[73,86,144]],["mediawiki.special.userlogin.signup.styles","zdhwz"],["mediawiki.special.userrights","1bv05",[28,96]],["mediawiki.special.watchlist","ehl7s",[73,144,95,235]],["mediawiki.special.version","18a2m"],["mediawiki.legacy.config","1id7p"],["mediawiki.legacy.commonPrint","10l65"],["mediawiki.legacy.protect","alqrg",[28]],["mediawiki.legacy.shared","6hmnd"],["mediawiki.legacy.oldshared","1x521"],["mediawiki.ui","grfr8"],["mediawiki.ui.checkbox","19ydy"],["mediawiki.ui.radio","sz6ub"],["mediawiki.ui.anchor","17xvi"],["mediawiki.ui.button","xq13z"],["mediawiki.ui.input","1niha"],["mediawiki.ui.icon","7elv3"],["mediawiki.ui.text","1kufo"],["mediawiki.widgets","1e6ei",[73,95,206,238,248]],["mediawiki.widgets.styles","1lu3s"],["mediawiki.widgets.AbandonEditDialog","1jlx1",[243]],["mediawiki.widgets.DateInputWidget","okd2k",[209,68,238,259]],["mediawiki.widgets.DateInputWidget.styles",
                            "ucyun"],["mediawiki.widgets.visibleLengthLimit","19md3",[28,235]],["mediawiki.widgets.datetime","19xpu",[116,235,258,259]],["mediawiki.widgets.expiry","a2ud6",[211,68,238]],["mediawiki.widgets.CheckMatrixWidget","12vdi",[235]],["mediawiki.widgets.CategoryMultiselectWidget","ol5yo",[81,238]],["mediawiki.widgets.SelectWithInputWidget","uncuq",[216,238]],["mediawiki.widgets.SelectWithInputWidget.styles","q1c8x"],["mediawiki.widgets.SizeFilterWidget","1nl7b",[218,238]],["mediawiki.widgets.SizeFilterWidget.styles","1r6fj"],["mediawiki.widgets.MediaSearch","5e4uw",[81,238]],["mediawiki.widgets.UserInputWidget","10vn6",[73,238]],["mediawiki.widgets.UsersMultiselectWidget","1yfyg",[73,238]],["mediawiki.widgets.NamespacesMultiselectWidget","15h9v",[238]],["mediawiki.widgets.TitlesMultiselectWidget","52c3w",[205]],["mediawiki.widgets.TagMultiselectWidget.styles","5095j"],["mediawiki.widgets.SearchInputWidget","04zk0",[102,205,254]],["mediawiki.widgets.SearchInputWidget.styles","1exn1"],[
                            "mediawiki.widgets.StashedFileWidget","o342x",[73,235]],["easy-deflate.core","16wf6"],["easy-deflate.deflate","9xn2e",[228]],["easy-deflate.inflate","1hu0a",[228]],["oojs","16ag5"],["mediawiki.router","ogol2",[233]],["oojs-router","17etn",[231]],["oojs-ui","3m146",[241,238,243]],["oojs-ui-core","yxbf2",[141,231,237,236,245]],["oojs-ui-core.styles","41tuo"],["oojs-ui-core.icons","ea6ke"],["oojs-ui-widgets","dgt4y",[235,240]],["oojs-ui-widgets.styles","dsl04"],["oojs-ui-widgets.icons","jm6ld"],["oojs-ui-toolbars","fe2as",[235,242]],["oojs-ui-toolbars.icons","1wm0a"],["oojs-ui-windows","1c886",[235,244]],["oojs-ui-windows.icons","1va00"],["oojs-ui.styles.indicators","kegfw"],["oojs-ui.styles.icons-accessibility","1nvu5"],["oojs-ui.styles.icons-alerts","1ggx0"],["oojs-ui.styles.icons-content","5m2fc"],["oojs-ui.styles.icons-editing-advanced","124ht"],["oojs-ui.styles.icons-editing-citation","1ap6o"],["oojs-ui.styles.icons-editing-core","hbhys"],["oojs-ui.styles.icons-editing-list","1socf"]
                            ,["oojs-ui.styles.icons-editing-styling","ysyti"],["oojs-ui.styles.icons-interactions","1rhhh"],["oojs-ui.styles.icons-layout","jfnyq"],["oojs-ui.styles.icons-location","w4fzx"],["oojs-ui.styles.icons-media","c3869"],["oojs-ui.styles.icons-moderation","i60zs"],["oojs-ui.styles.icons-movement","drekn"],["oojs-ui.styles.icons-user","uyeyu"],["oojs-ui.styles.icons-wikimedia","8l1x7"],["skins.monobook.styles","1wl51"],["skins.monobook.responsive","1x437"],["skins.monobook.mobile","u7zsn",[116]],["skins.timeless","3c1v5"],["skins.timeless.js","1smv0",[34]],["skins.timeless.mobile","1cd55"],["skins.vector.styles","1dag6"],["skins.vector.styles.responsive","fd58t"],["skins.vector.js","gi7hc",[34,116]]]);mw.config.set({"debug":!1,"skin":"vector","stylepath":"/wiki/skins","wgUrlProtocols":
                            "bitcoin\\:|ftp\\:\\/\\/|ftps\\:\\/\\/|geo\\:|git\\:\\/\\/|gopher\\:\\/\\/|http\\:\\/\\/|https\\:\\/\\/|irc\\:\\/\\/|ircs\\:\\/\\/|magnet\\:|mailto\\:|mms\\:\\/\\/|news\\:|nntp\\:\\/\\/|redis\\:\\/\\/|sftp\\:\\/\\/|sip\\:|sips\\:|sms\\:|ssh\\:\\/\\/|svn\\:\\/\\/|tel\\:|telnet\\:\\/\\/|urn\\:|worldwind\\:\\/\\/|xmpp\\:|\\/\\/","wgArticlePath":"/wiki/$1","wgScriptPath":"/mediawiki","wgScript":"/wiki/index.php","wgSearchType":null,"wgVariantArticlePath":!1,"wgActionPaths":{},"wgServer":"","wgServerName":"www.apimba.org","wgUserLanguage":"en","wgContentLanguage":"en","wgTranslateNumerals":!0,"wgVersion":"1.34.0","wgEnableAPI":!0,"wgEnableWriteAPI":!0,"wgFormattedNamespaces":{"-2":"Media","-1":"Special","0":"","1":"Talk","2":"User","3":"User
                            talk","4":"Apimba","5":"Apimba talk","6":"File","7":"File
                            talk","8":"MediaWiki","9":"MediaWiki talk","10":"Template","11":"Template
                            talk","12":"Help","13":"Help talk","14":"Category","15":
                            "Category
                            talk"},"wgNamespaceIds":{"media":-2,"special":-1,"":0,"talk":1,"user":2,"user_talk":3,"apimba":4,"apimba_talk":5,"file":6,"file_talk":7,"mediawiki":8,"mediawiki_talk":9,"template":10,"template_talk":11,"help":12,"help_talk":13,"category":14,"category_talk":15,"image":6,"image_talk":7,"project":4,"project_talk":5},"wgContentNamespaces":[0],"wgSiteName":"apimba","wgDBname":"apimba","wgWikiID":"apimba","wgExtraSignatureNamespaces":[],"wgExtensionAssetsPath":"/wiki/extensions","wgCookiePrefix":"apimba","wgCookieDomain":"","wgCookiePath":"/","wgCookieExpiration":2592000,"wgCaseSensitiveNamespaces":[],"wgLegalTitleChars":"
                            %!\"$\u0026'()*,\\-./0-9:;=?@A-Z\\\\\\^_`a-z~+\\u0080-\\uFFFF","wgIllegalFileChars":":/\\\\","wgForeignUploadTargets":["local"],"wgEnableUploads":!0,"wgCommentByteLimit":null,"wgCommentCodePointLimit":500});mw.config.set(window.RLCONF||{});mw.loader.state(window.RLSTATE||{});mw.loader.load(window.RLPAGEMODULES||[]);RLQ=window.RLQ||[];RLQ.push=function(fn){
                            if(typeof
                            fn==='function'){fn();}else{RLQ[RLQ.length]=fn;}};while(RLQ[0]){RLQ.push(RLQ.shift());}NORLQ={push:function(){}};}());}