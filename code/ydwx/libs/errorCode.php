<?php

/**
 * error code 说明.
 * <ul>
 *    <li>-40001: 签名验证错误</li>
 *    <li>-40002: xml解析失败</li>
 *    <li>-40003: sha加密生成签名失败</li>
 *    <li>-40004: encodingAesKey 非法</li>
 *    <li>-40005: appid 校验错误</li>
 *    <li>-40006: aes 加密失败</li>
 *    <li>-40007: aes 解密失败</li>
 *    <li>-40008: 解密后得到的buffer非法</li>
 *    <li>-40009: base64加密失败</li>
 *    <li>-40010: base64解密失败</li>
 *    <li>-40011: 生成xml失败</li>
 * </ul>
 */
class ErrorCode
{
	public static $OK = 0;
	public static $ValidateSignatureError = -40001;
	public static $ParseXmlError = -40002;
	public static $ComputeSignatureError = -40003;
	public static $IllegalAesKey = -40004;
	public static $ValidateAppidError = -40005;
	public static $EncryptAESError = -40006;
	public static $DecryptAESError = -40007;
	public static $IllegalBuffer = -40008;
	public static $EncodeBase64Error = -40009;
	public static $DecodeBase64Error = -40010;
	public static $GenReturnXmlError = -40011;
}

class ErrorCodeZH{
    public static function common($errcode){
        switch ($errcode){
case -1:    return "微信系统错误";
case 40001:	return "获取access_token时AppSecret错误，或者access_token无效。请开发者认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口";
case 40002:	return "不合法的凭证类型";
case 40003:	return "不合法的OpenID，请开发者确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID";
case 40004:	return "不合法的媒体文件类型";
case 40005:	return "不合法的文件类型";
case 40006:	return "不合法的文件大小";
case 40007:	return "不合法的媒体文件id";
case 40008:	return "不合法的消息类型";
case 40009:	return "不合法的图片文件大小";
case 40010:	return "不合法的语音文件大小";
case 40011:	return "不合法的视频文件大小";
case 40012:	return "不合法的缩略图文件大小";
case 40013:	return "不合法的AppID，请开发者检查AppID的正确性，避免异常字符，注意大小写";
case 40014:	return "不合法的access_token，请开发者认真比对access_token的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口";
case 40015:	return "不合法的菜单类型";
case 40016:	return "不合法的按钮个数";
case 40017:	return "不合法的按钮个数";
case 40018:	return "不合法的按钮名字长度";
case 40019:	return "不合法的按钮KEY长度";
case 40020:	return "不合法的按钮URL长度";
case 40021:	return "不合法的菜单版本号";
case 40022:	return "不合法的子菜单级数";
case 40023:	return "不合法的子菜单按钮个数";
case 40024:	return "不合法的子菜单按钮类型";
case 40025:	return "不合法的子菜单按钮名字长度";
case 40026:	return "不合法的子菜单按钮KEY长度";
case 40027:	return "不合法的子菜单按钮URL长度";
case 40028:	return "不合法的自定义菜单使用用户";
case 40029:	return "不合法的oauth_code";
case 40030:	return "不合法的refresh_token";
case 40031:	return "不合法的openid列表";
case 40032:	return "不合法的openid列表长度";
case 40033:	return "不合法的请求字符，不能包含\uxxxx格式的字符";
case 40035:	return "不合法的参数";
case 40038:	return "不合法的请求格式";
case 40039:	return "不合法的URL长度";
case 40050:	return "不合法的分组id";
case 40051:	return "分组名字不合法";
case 40053:	return "不合法的actioninfo，请开发者确认参数正确。";
case 40056:	return "不合法的Code码。";
case 40071:	return "不合法的卡券类型。";
case 40072:	return "不合法的编码方式。";
case 40078:	return "不合法的卡券状态。";
case 40079:	return "不合法的时间。";
case 40080:	return "不合法的CardExt。";
case 40094: return "参数不正确，请检查json 字段";
case 40099:	return "卡券已被核销。";

case 40100:	return "不合法的时间区间。";
case 40116:	return "不合法的Code码。";
case 40117:	return "分组名字不合法";
case 40118:	return "media_id大小不合法";
case 40119:	return "button类型错误";
case 40120:	return "button类型错误";
case 40121:	return "不合法的media_id类型";
case 40132:	return "微信号不合法";
case 40137:	return "不支持的图片格式";
case 40122:	return "不合法的库存数量。";
case 40124:	return "会员卡设置查过限制的 custom_field字段。";
case 40127:	return "卡券被用户删除或转赠中。";

case 41001:	return "缺少access_token参数";
case 41002:	return "缺少appid参数";
case 41003:	return "缺少refresh_token参数";
case 41004:	return "缺少secret参数";
case 41005:	return "缺少多媒体文件数据";
case 41006:	return "缺少media_id参数";
case 41007:	return "缺少子菜单数据";
case 41008:	return "缺少oauth code";
case 41009:	return "缺少openid";
case 41011:	return "缺少必填字段。";
case 41012:	return "缺少cardid参数。";

case 42001:	return "access_token超时，请检查access_token的有效期，请参考基础支持-获取access_token中，对access_token的详细机制说明";
case 42002:	return "refresh_token超时";
case 42003:	return "oauth_code超时";

case 43001:	return "需要GET请求";
case 43002:	return "需要POST请求";
case 43003:	return "需要HTTPS请求";
case 43004:	return "需要接收者关注";
case 43005:	return "需要好友关系";
case 43009:	return "自定义SN权限，请前往公众平台申请。";
case 43010:	return "无储值权限，请前往公众平台申请。";

case 44001:	return "多媒体文件为空";
case 44002:	return "POST的数据包为空";
case 44003:	return "图文消息内容为空";
case 44004:	return "文本消息内容为空";

case 45001:	return "多媒体文件大小超过限制";
case 45002:	return "消息内容超过限制";
case 45003:	return "标题字段超过限制";
case 45004:	return "描述字段超过限制";
case 45005:	return "链接字段超过限制";
case 45006:	return "图片链接字段超过限制";
case 45007:	return "语音播放时间超过限制";
case 45008:	return "图文消息超过限制";
case 45009:	return "接口调用超过限制";
case 45010:	return "创建菜单个数超过限制";
case 45015:	return "回复时间超过限制";
case 45016:	return "系统分组，不允许修改";
case 45017:	return "分组名字过长";
case 45018:	return "分组数量超过上限";
case 45021:	return "字段超过长度限制，请参考相应接口的字段说明。";
case 45030:	return "该cardid无接口权限。";
case 45031:	return "库存为0。";
case 45033:	return "用户领取次数超过限制get_limit";

case 46001:	return "不存在媒体数据";
case 46002:	return "不存在的菜单版本";
case 46003:	return "不存在的菜单数据";
case 46004:	return "不存在的用户";

case 47001:	return "解析JSON/XML内容错误";

case 48001:	return "api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限";

case 50001:	return "用户未授权该api";
case 50002:	return "用户受限，可能是违规后接口被封禁";

case 61451:	return "参数错误(invalid parameter)";
case 61452:	return "无效客服账号(invalid kf_account)";
case 61453:	return "客服帐号已存在(kf_account exsited)";
case 61454:	return "客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid kf_acount length)";
case 61455:	return "客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)";
case 61456:	return "客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)";
case 61457:	return "无效头像文件类型(invalid file type)";
case 61450:	return "系统错误(system error)";
case 61500:	return "日期格式错误";
case 61501:	return "日期范围错误";

case 65104: return "门店的类型不合法，必须严格按照附表的分类填写";
case 65105: return "图片url 不合法，必须使用接口1 的图片上传接口所获取的url";
case 65106: return "门店状态必须未审核通过";
case 65107: return "扩展字段为不允许修改的状态";
case 65109: return "门店名为空";
case 65110: return "门店所在详细街道地址为空";
case 65111: return "门店的电话为空";
case 65112: return "门店所在的城市为空";
case 65113: return "门店所在的省份为空";
case 65114: return "图片列表为空";
case 65115: return "poi_id 不正确";

case 9001001:	return "POST数据参数不合法";
case 9001002:	return "远端服务不可用";
case 9001003:	return "Ticket不合法";
case 9001004:	return "获取摇周边用户信息失败";
case 9001005:	return "获取商户信息失败";
case 9001006:	return "获取OpenID失败";
case 9001007:	return "上传文件缺失";
case 9001008:	return "上传素材的文件类型不合法";
case 9001009:	return "上传素材的文件尺寸不合法";
case 9001010:	return "上传失败";
case 9001020:	return "帐号不合法";
case 9001021:	return "已有设备激活率低于50%，不能新增设备";
case 9001022:	return "设备申请数不合法，必须为大于0的数字";
case 9001023:	return "已存在审核中的设备ID申请";
case 9001024:	return "一次查询设备ID数量不能超过50";
case 9001025:	return "设备ID不合法";
case 9001026:	return "页面ID不合法";
case 9001027:	return "页面参数不合法";
case 9001028:	return "一次删除页面ID数量不能超过10";
case 9001029:	return "页面已应用在设备中，请先解除应用关系再删除";
case 9001030:	return "一次查询页面ID数量不能超过50";
case 9001031:	return "时间区间不合法";
case 9001032:	return "保存设备与页面的绑定关系参数错误";
case 9001033:	return "门店ID不合法";
case 9001034:	return "设备备注信息过长";
case 9001035:	return "设备申请参数不合法";
case 9001036:	return "查询起始值begin不合法";
case 9001037:	return "单个设备绑定页面不能超过30个";
case 9001038:	return "设备总数超过了限额";
case 9001039:	return "不合法的联系人名字";
case 9001040:	return "不合法的联系人电话";
case 9001041:	return "不合法的联系人邮箱";
case 9001042:	return "不合法的行业id";
case 9001043:	return "不合法的资质证明文件url，文件需通过“素材管理”接口上传";
case 9001044:	return "缺少资质证明文件";
case 9001045:	return "申请理由不能超过500字";
case 9001046:	return "公众账号未认证";
case 9001047:	return "不合法的设备申请批次id";
case 9001048:	return "审核状态为审核中或审核已通过，不能再提交申请请求";
case 9001049:	return "获取分组元数据失败";
case 9001050:	return "账号下分组数达到上限，最多为100个";
case 9001051:	return "分组包含的设备数达到上限，最多为10000个";
case 9001052:	return "每次添加到分组的设备数达到上限，每次最多操作1000个设备";
case 9001053:	return "每次从分组删除的设备数达到上限，每次最多操作1000个设备";
case 9001054:	return "待删除的分组仍存在设备";
case 9001055:	return "分组名称过长，上限为100个字符";
case 9001056:	return "分组待添加或删除的设备列表中包含有不属于该分组的设备id";
case 9001057:	return "分组相关信息操作失败";
case 9001058:	return "分组id不存在";
case 9001059:	return "模板页面logo_url为空";
case 9001060:	return "创建红包活动失败";
case 9001061:	return "获得红包活动ID失败";
case 9001062:	return "创建模板页面失败";
case 9001063:	return "红包提供商户公众号ID和红包发放商户公众号ID不一致";
case 9001064:	return "红包权限审核失败";
case 9001065:	return "红包权限正在审核";
case 9001066:	return "红包权限被取消";
case 9001067:	return "没有红包权限";
case 9001068:	return "红包活动时间不在红包权限有效时间内";
case 9001069:	return "设置红包活动开关失败";
case 9001070:	return "获得红包活动信息失败";
case 9001071:	return "查询红包ticket失败";
case 9001072:	return "红包ticket数量超过限制";
case 9001073:	return "sponsor_appid与预下单时的wxappid不一致";
case 9001074:	return "获得红包发送ID失败";
case 9001075:	return "录入活动的红包总数超过创建活动时预设的total";
case 9001076:	return "添加红包发送ID失败";
case 9001077:	return "解码红包发送ID失败";
case 9001078:	return "获取公众号uin失败";
case 9001079:	return "接口调用appid与调用创建活动接口的appid不一致";
case 9001090:	return "录入的所有ticket都是无效ticket，可能原因为ticket重复使用，过期或金额不在1-1000元之间";
case 9001091:	return "活动已过期";
default :return null;
        }
    }
}
?>