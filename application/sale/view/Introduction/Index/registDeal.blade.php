<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2//mobile/css/amazeui.min.css"> -->
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
</head>

<body>
<?php if($type == '' && empty($_v)){ ?>
    <header class="page-header">
            
            <div class="page-bar">
            
            <a href="/user/index/index">
                <img src="/mobile/img/icon/back@2x.png" class="back-ico">
            </a>
            
            <div class="bar-title">注册协议</div>
        </div>
    </header>
 <?php } ?>
 <section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }'>
 	 <div class="am-accordion-content"><p class="text-indent">
 	《新牛牛汇商家版服务协议》（以下简称“本协议”）由您与新牛牛汇商家版共同缔结，本协议具有合同效力。为使用新牛牛汇商家版服务，您应当阅读并遵守本协议。请您务必审慎阅读、充分理解各条款内容，特别是免除或者限制责任的条款，以及开通或使用某项服务的单独协议。除非您已阅读并接受本协议所有条款，否则您无权使用新牛牛汇商家版提供的服务。您使用新牛牛汇商家版服务即视为您已阅读并同意上述协议的约束。如果您未满18周岁，请在法定监护人的陪同下阅读本协议，并特别注意未成年人使用条款。
 	</p></div>
      <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        一、 本协议的订立
        </dt>
        <dd class="am-accordion-bd am-collapse">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
          <div class="am-accordion-content">
            	<p class="text-indent">1.1本协议由新牛牛汇商家版与您共同缔结，具有合同效力。</p>
				<p class="text-indent">1.2 您一经勾选“【同意】”并使用新牛牛汇商家版许可登陆的账户登陆新牛牛汇商家版，即意味着您同意与新牛牛汇商家版签订本协议并自愿受本协议约束，并对新牛牛汇商家版与您之间具有法律效力。</p>
				<p class="text-indent">1.3本协议指本协议正文、新牛牛汇APP的平台规则、其修订版本以及其他新牛牛汇商家版所列明的所有规则性文件、通知或其他内容。上述内容一经正式发布，即为本协议不可分割的组成部分，您同样应当遵守。您对前述任何协议、规则的接受，即视为您对本协议全部的接受。</p>
				<p class="text-indent">1.4本协议各条的标题仅为方便阅读而设，无意成为本协议的一部分，也不影响本协议的含义或解释。</p>
          </div>
        </dd>
      </dl>
      <dl class="am-accordion-item">
        <dt class="am-accordion-title">
         二、定义和解释
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
          <div class="am-accordion-content">
			<p class="text-indent">2.1 新牛牛汇商家版：指由新牛牛汇商家版提供技术支持和服务的电子商务平台，为包括但不限于：本站以及新牛牛汇商家版提供技术服务或运营的移动端交易平台。随着新牛牛汇商家版服务范围或服务项目的变更，新牛牛汇商家版可能在平台规则或公告中对新牛牛汇商家版的范围或域名调整予以声明。</p>
			<p class="text-indent">2.2 新牛牛汇商家版开放平台：为商家提供线上商城、线下店铺服务和管理，使用商家权限，并与顾客达成交易意向的特定网络空间。</p>
			<p class="text-indent">2.3 平台规则：指在各新牛牛汇商家版上已经发布或将来可能发布的各种规范性文件，包括但不限于细则、规范、政策、通知等规范性文件。</p>
			<p class="text-indent">2.4 平台服务：指新牛牛汇商家版依托新牛牛汇商家版向用户提供的网络空间、技术支持、相关的软件服务、系统维护，以及同意向用户提供的各项附属功能、增值服务等。包括商品发布、浏览、信息交流、商品交易等服务，具体服务内容及功能以新牛牛汇商家版在各个平台上提供的具体服务内容为准。</p>
            <?php if($v != 1 ){ ?>
    			<p class="text-indent">2.5 用户：指使用新牛牛汇商家版服务的自然人、法人或其他组织，本协议中又称“您”。</p>
    			<p class="text-indent">2.6 消费者：指在新牛牛汇商家版上消费购买商品的用户。</p>
    			<p class="text-indent">2.7消费商：指在新牛牛汇商家版上消费满一定金额的用户并分享给他人从而获取一定消费提成的用户。</p>
    			<p class="text-indent">2.8商家：指在新牛牛汇商家版上出售和提供商品及自愿让利给平台的用户。</p>

    			<p class="text-indent">2.9 商品：包括产品和服务。</p>
    			<p class="text-indent">2.10 账户：或称“新牛牛汇商家版账户”，指用户所拥有的经新牛牛汇商家版认可，可以登陆新牛牛汇商家版的一个合法获得并持有的服务账户（该账户形式可能是在新牛牛汇商城注册所获得的新牛牛汇商城账户、网站账户、手机号、电子邮箱等新牛牛汇商城支持的其他形式账户，具体哪种形式账户可以登陆新牛牛汇商城，以新牛牛汇商城公布为准。</p>
    			<p class="text-indent">2.11 互联网支付服务：指具有互联网支付合法资质的第三方支付机构为用户完成交易、转移资金提供的支付服务，详情见该第三方支付机构通过用户与其所签订的约定其所提供支付服务的协议、公司网站、电子邮件或其他形式所公布的相关规则及说明。</p>
    			<p class="text-indent">2.12 关联公司：本协议所称一方的“关联公司”是指由一方直接控制或间接控制；或直接或间接控制一方；或与一方共同控制同一家公司或能对其施加重大影响；或与一方受同一家公司直接或间接控制的公司。包括但不限于一方的母公司、子公司；与一方受同一母公司控制的子公司；一方的合营企业、联营企业等。“控制”是指直接或间接地拥有影响所提及公司管理的能力，无论是通过所有权、有投票权的股份、合同或其他被人民法院认定的方式。</p>
            <?php }else{ ?> 
                <p class="text-indent">2.5 商品：包括产品和服务。</p>
                <p class="text-indent">2.6 账户：或称“新牛牛汇商家版账户”，指用户所拥有的经新牛牛汇商家版认可，可以登陆新牛牛汇商家版的一个合法获得并持有的服务账户（该账户形式可能是在新牛牛汇商城注册所获得的新牛牛汇商城账户、网站账户、手机号、电子邮箱等新牛牛汇商城支持的其他形式账户，具体哪种形式账户可以登陆新牛牛汇商城，以新牛牛汇商城公布为准。</p>
                <p class="text-indent">2.7 互联网支付服务：指具有互联网支付合法资质的第三方支付机构为用户完成交易、转移资金提供的支付服务，详情见该第三方支付机构通过用户与其所签订的约定其所提供支付服务的协议、公司网站、电子邮件或其他形式所公布的相关规则及说明。</p>
                <p class="text-indent">2.8 关联公司：本协议所称一方的“关联公司”是指由一方直接控制或间接控制；或直接或间接控制一方；或与一方共同控制同一家公司或能对其施加重大影响；或与一方受同一家公司直接或间接控制的公司。包括但不限于一方的母公司、子公司；与一方受同一母公司控制的子公司；一方的合营企业、联营企业等。“控制”是指直接或间接地拥有影响所提及公司管理的能力，无论是通过所有权、有投票权的股份、合同或其他被人民法院认定的方式。</p>
            <?php } ?>
          </div>
        </dd>
      </dl>

      <dl class="am-accordion-item">
        <dt class="am-accordion-title">
         三、协议的生效与变更
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
          <div class="am-accordion-content">
           	<p class="text-indent">3.1 新牛牛汇商家版有权在必要时修改本协议条款并会在修改生效前以公告的形式向您告知。您可以在新牛牛汇商家版的相关页面查阅最新版本的协议条款。</p>
			<p class="text-indent">3.2 本协议条款变更后，如果您继续使用新牛牛汇商家版提供的软件或服务，即视为您已接受修改后的协议。如果您不接受修改后的协议，应终止本协议，并停止使用新牛牛汇商家版提供的软件或服务。</p>
          </div>
        </dd>
      </dl>

       <dl class="am-accordion-item">
        <dt class="am-accordion-title">
          四、注册用户和账户
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
          	<div class="am-accordion-content">
				<p class="text-indent">4.1 用户使用新牛牛汇商家版服务应具备完全民事行为能力；用户若不具备前述资格，则用户的监护人应承担因此而导致的一切后果，且新牛牛汇商家版有权注销或永久冻结用户的账户。</p>
				<p class="text-indent">4.2 用户使用新牛牛汇商家版服务必须拥有经新牛牛汇商家版认可登陆新牛牛汇商家版的一个合法获得并持有的服务账户；用户对前述账户的申请、使用等行为应符合本协议及注册该账户时与相应的第三方账户服务提供者所签订的注册协议、服务协议以及其他有关规则。用户应自行确保上述账户及其密码的安全，并对利用上述账户及其密码所进行的一切行为负完全责任。</p>
				<p class="text-indent">4.3您设置的用户名不得违反国家法律法规，否则新牛牛汇商家版可回收您的用户名。</p>
				<p class="text-indent">4.4 您理解并同意，若您的行为违反本协议的相关规定，您的用户资格和账户可能被注销、暂时冻结或永久冻结。</p>
			</div>
        </dd>
      </dl>

       <dl class="am-accordion-item">
        <dt class="am-accordion-title">
         五、商家进驻管理和费用
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
           <div class="am-accordion-content">
          		<p class="text-indent">5.1您方应主动向新牛牛汇商家版提供为建设网上合作平台所需要的信息、介绍、市场及宣传等文档内容；您方负责办理网上经营许可的各种资质。</p>
				<p class="text-indent">5.2 您方应缴纳一定的平台使用费和技术服务费，缴费协议双方将另行签订补充协议。缴纳费用后可以使用新牛牛汇商家版平台完善的系统（管理系统 支付系统）。电商平台管理系统，在前台可帮助企业整理店面，在后台按商家要求设立商店管理员权限，为商家在网上管理商店提供条件和工具：包括定单查询跟踪、销售跟踪，帮助企业积累客户数据，缩短企业与客户之间的中间环节，建立直接联系。商家同时享受商城完善的网上支付服务系统，支付方式包括：微信付款、支付宝付款、邮政汇款、银行汇款、工商银行牡丹信用卡、工商银行牡丹灵通卡、招商银行一网通以及首信支付平台（包括并不限于：中国银行借记卡，中国银行长城卡，工商银行存折，建设银行龙卡）等等,帮助解决客户支付问题。我们为消费者提供个性化的、便捷的网上购物。</p>
          </div>
        </dd>
      </dl>

       <dl class="am-accordion-item">
        <dt class="am-accordion-title">
         六、【责任限制】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
           <div class="am-accordion-content">
		        <p class="text-indent">6.1 您理解并同意，新牛牛汇商家版APP平台会在现有技术水平和条件下尽最大努力向您提供服务，确保服务的连贯性和安全性；但新牛牛汇商家版APP平台不能随时预见和防范法律、技术以及其他风险，包括但不限于不可抗力，大规模的病毒、木马和黑客攻击，系统不稳定，第三方服务瑕疵，政府管制等原因可能导致的服务中断、数据丢失以及其他的损失和风险。
				<p class="text-indent">6.2 用户已充分知悉和同意：新牛牛汇商家版APP平台平台是新牛牛汇A商家版PP平台为用户提供的信息交流及商品交易的电子商务平台，新牛牛汇商家版APP平台仅根据本协议为用户提供新牛牛汇商家版APP平台平台服务。在新牛牛汇商家版APP平台平台上，用户应对自身发布信息、进行商品交易的真实性、合法性、安全性独立承担责任；新牛牛汇商家版APP平台不对商品质量、权利瑕疵以及买卖双方履行交易协议而产生的问题承担任何责任。</p>
				<p class="text-indent">6.3 您同意，对新牛牛汇商家版APP平台平台上出现的网络链接信息，您应审慎判断其真实性和可靠性，除法律明确规定外，您应对依该链接信息进行的交易负责。</p>
				<p class="text-indent">6.4 用户理解并同意，在使用新牛牛汇商家版APP平台平台服务的过程中，可能会遇到不可抗力等风险因素使新牛牛汇商家版APP平台平台服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，新牛牛汇商家版APP平台将努力在第一时间与相关单位配合，及时进行修复，但是由此给用户造成的损失，新牛牛汇商家版APP平台将在法律允许的范围内免责。</p>
				<p class="text-indent">6.5 在法律允许的范围内，新牛牛汇商家版APP平台对以下情形之一导致的服务中断或受阻不承担责任：
				(1)   受到计算机病毒、木马或其他恶意程序、黑客攻击的破坏；
				(2)   用户或新牛牛汇商家版APP平台的电脑软件、系统、硬件和通信线路出现故障；
				(3)   用户操作不当；
				(4)   用户通过非新牛牛汇商家版APP平台授权的方式使用服务；
				(5)   其他新牛牛汇商家版APP平台无法控制或合理预见的情形。</p>
				<p class="text-indent">6.6 新牛牛汇商家版APP平台依据本协议约定获得处理违法违规内容的权利，该权利不构成新牛牛汇商家版APP平台的义务或承诺，新牛牛汇商家版APP平台不能保证及时发现违法行为或进行相应处理。</p>
          </div>
        </dd>
      </dl>

    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        七、【服务中止和终止】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
           <div class="am-accordion-content">
	       		<p class="text-indent">7.1 如发生下列任何一种情形，新牛牛汇商家版APP平台有权不经通知而中断、中止或终止向用户提供的服务，且不向该用户承担任何责任：
				(1)   用户未按本协议提供真实信息；
				(2)   用户违反相关法律法规或本协议的规定；
				(3)   按照法律规定或主管部门的要求；
				(4)   用户侵犯其他第三方合法权益的；
				(5)   出于安全的原因或其他必要的情形。</p>
				<p class="text-indent">7.2 用户有责任自行备份存储在使用新牛牛汇商家版APP平台平台服务中产生的数据和信息。</p>
				<p class="text-indent">7.3 您同意，即便在本协议终止及您的服务被终止后，新牛牛汇商家版APP平台仍有权：
				(1)   继续保存并使用您的用户信息；</p>
				(2)   继续向您主张您在使用新牛牛汇商家版APP平台平台服务期间因违反法律法规、本协议及平台规则而应承担的责任。
				<p class="text-indent">7.4 对于您在新牛牛汇商家版APP平台平台服务中止或终止之前已经上传至新牛牛汇商家版APP平台平台的商品，因新牛牛汇商家版APP平台依据本协议中止或终止向您提供新牛牛汇商家版APP平台平台服务，而导致您无法与其他用户达成交易，或虽已达成交易但无法实际履行或无法完全履行，因而造成您损失的，新牛牛汇商家版APP平台不承担责任。</p>
          </div>
        </dd>
    </dl>

    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        八、【本服务软件形式】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		       	<p class="text-indent">若新牛牛汇商家版APP平台依托“软件”向您提供新牛牛汇商家版APP平台平台服务，您还应遵守以下约定：</p>
				<p class="text-indent">8.1 新牛牛汇商家版APP平台可能为不同的终端设备开发不同的软件版本，您应当根据实际需要选择下载合适的版本进行安装。</p>
				<p class="text-indent">8.2 如果您从未经合法授权的第三方获取本软件或与本软件名称相同的安装程序，新牛牛汇商家版APP平台将无法保证该软件能否正常使用，并对因此给您造成的任何损失不予负责。</p>
				<p class="text-indent">8.3 为了增进用户体验、完善服务内容，新牛牛汇商家版APP平台将不时提供软件更新服务(该更新可能会采取软件替换、修改、功能强化、版本升级等形式)。为了改善用户体验，保证服务的安全性和功能的一致性，新牛牛汇商家版APP平台有权对软件进行更新或者对软件的部分功能效果进行改变或限制。</p>
				<p class="text-indent">8.4 软件新版本发布后，旧版软件可能无法使用。新牛牛汇商家版APP平台不保证旧版软件继续可用及相应的客户服务，请您随时核对并下载最新版本。</p>
				<p class="text-indent">8.5 除非法律允许或新牛牛汇商家版APP平台书面许可，您不得从事下列行为：
				(1)   删除软件及其副本上关于著作权的信息；
				(2)   对软件进行反向工程、反向汇编、反向编译或者以其他方式尝试发现软件的源代码；
				(3)   对新牛牛汇商家版APP平台拥有知识产权的内容进行使用、出租、出借、复制、修改、链接、转载、汇编、发表、出版、建立镜像站点等；
				(4)   对软件或者软件运行过程中释放到任何终端内存中的数据、软件运行过程中客户端与服务器端的交互数据，以及软件运行所必需的系统数据，进行复制、修改、增加、删除、挂接运行或创作任何衍生作品，形式包括但不限于使用插件、外挂或非经合法授权的第三方工具/服务接入软件和相关系统；
				(5)   修改或伪造软件运行中的指令、数据，增加、删减、变动软件的功能或运行效果，或者将用于上述用途的软件、方法进行运营或向公众传播，无论上述行为是否为商业目的；
				(6)   通过非新牛牛汇商家版APP平台开发、授权的第三方软件、插件、外挂、系统，使用新牛牛汇商家版APP平台平台服务，或制作、发布、传播非新牛牛汇商家版APP平台开发、授权的第三方软件、插件、外挂、系统；
				(7)   其他未经新牛牛汇商家版APP平台明示授权的行为。</p>
	        </div>
        </dd>
    </dl>

    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        九、【知识产权】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		        <p class="text-indent">9.1 新牛牛汇商家版APP平台在提供新牛牛汇商家版APP平台平台服务中提供的信息内容，包括但不限于网页、文字、图片、音频、视频、图表等的知识产权均归新牛牛汇商家版APP平台所有，用户在使用新牛牛汇商家版APP平台平台服务中所产生的内容的知识产权归用户或相关权利人所有。</p>
				<p class="text-indent">9.2 除另有特别声明外，新牛牛汇商家版APP平台提供新牛牛汇商家版APP平台平台服务时所依托软件的著作权、专利权及其他知识产权均归新牛牛汇APP商家版平台或其关联公司所有。</p>
				<p class="text-indent">9.3 如您通过新牛牛汇商家版APP平台推出的客户端软件使用新牛牛汇商家版APP平台平台服务，新牛牛汇商家版APP平台给予您一项个人的、不可转让、非独占及非排他性的许可，您只能为正当使用新牛牛汇商家版APP平台平台服务之目的使用该权利，不得将其用作任何目的，也不得随意复制、修改、编译或以任何其他方式处置这些权利。</p>
	        </div>
        </dd>
    </dl>

    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        十、【遵守当地法律监管】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		       	<p class="text-indent">10.1 您在使用新牛牛汇商家版APP平台平台服务过程中应当遵守当地相关的法律法规，并尊重当地的道德和风俗习惯。如果您的行为违反了当地法律法规或道德风俗，您应当为此独立承担责任。</p>
				<p class="text-indent">10.2 您应避免因使用新牛牛汇商家版APP平台平台服务而使新牛牛汇商家版APP平台卷入政治和公共事件，否则新牛牛汇商家版APP平台有权暂停或终止对您的服务。</p>

	        </div>
        </dd>
    </dl>


	<dl class="am-accordion-item">
        <dt class="am-accordion-title">
        十一、【隐私政策】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		       	<p class="text-indent">保护用户个人信息及隐私是新牛牛汇商家版APP平台的一项基本原则。除本协议及平台规则另有规定外，新牛牛汇商家版APP平台隐私政策将遵循新牛牛汇商家版公司统一公布的相关隐私政策。</p>

	        </div>
        </dd>
    </dl>

    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
       十二、【管辖与法律适用】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		       	<p class="text-indent">12.1 本协议签订地为中华人民共和国广东省深圳市南山区。</p>
				<p class="text-indent">12.2 本协议的成立、生效、履行、解释及纠纷解决，适用中华人民共和国大陆地区法律（不包括冲突法）。</p>
				<p class="text-indent">12.3 若您和新牛牛汇商家版APP平台之间因本协议发生任何纠纷或争议，首先应友好协商解决；协商不成的，您同意将纠纷或争议提交至本协议签订地有管辖权的人民法院管辖。</p>
				<p class="text-indent">12.4 本协议所有条款的标题仅为阅读方便，本身并无实际涵义，不能作为本协议涵义解释的依据。</p>
				<p class="text-indent">12.5 本协议条款无论因何种原因部分无效，其余条款仍有效，对各方具有约束力。</p>


	        </div>
        </dd>
    </dl>


    <dl class="am-accordion-item">
        <dt class="am-accordion-title">
        十三、【其他】
        </dt>
        <dd class="am-accordion-bd am-collapse ">
          <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
            <div class="am-accordion-content">
		       	<p class="text-indent">13.1 新牛牛汇商家版APP平台提醒用户，网上交易有风险，您应该进行审慎判断。</p>
				<p class="text-indent">13.2 如果您对本协议或新牛牛汇商家版APP平台平台服务有意见或建议，可与新牛牛汇商家版APP平台客户服务部门联系，我们会给予您必要的帮助。</p>

	        </div>
        </dd>
    </dl>


</section>
	




</body>

</html>