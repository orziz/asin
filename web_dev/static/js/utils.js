/**
 * ==================================================
 * 基于 electron-vue 的扩展库
 * @author 子不语 <zz@pohun.com/1063614727@qq.com>
 * ==================================================
 */

import axios from 'axios'

class Utils {

  static getJsonFiles(jsonPath){
    let jsonFiles = [];
    function findJsonFile(path){
        let files = fs.readdirSync(path);
        files.forEach(function (item, index) {
            let fPath = join(path,item);
            let stat = fs.statSync(fPath);
            if(stat.isDirectory() === true) {
                findJsonFile(fPath);
            }
            if (stat.isFile() === true) { 
              jsonFiles.push(fPath);
            }
        });
    }
    findJsonFile(jsonPath);
    console.log(jsonFiles);
    return jsonFiles;
  }

  static setWindowTitle (title) {
    document.title = title;
  }

  static $post (obj) {
    console.log('=====网络请求=====');
    var param = {};
    for (var key in obj) {
      if (key != 'success' && key != 'fail' && key != 'url') param[key] = obj[key];
    }
    param.isapi = true;
    param.acctoken = true;
    // if (UData.userinfo) if (UData.userinfo.openid) param.openid = UData.userinfo.openid;
    console.log('param:::',param);
    param = JSON.stringify(param);
    var url = obj.url ? obj.url : _config.server;
    axios.post(url,{param: param})
      .then((res)=>{
        console.log(res);
        if (res.status == 200) {
          let data = res.data;
          if (data.errCode >= 200 && data.errCode < 300) {
            if (obj.success) obj.success(data.data);
          } else {
            if (obj.fail) obj.fail(data);
          }
        } else {
          if (obj.fail) obj.fail({errCode: res.status, errMsg: '网络请求失败'});
        }
      })
      .catch((err)=>{
        console.log('axios catch===>',err);
      })
  }

  static $postFile (obj) {
    if (!('url' in obj)) return;
    const instance=axios.create({
      withCredentials: true
    }) 
    let config = {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }
    console.log('=====提交文件=====');
    let formData = new FormData(obj.form);
    for (var key in obj) {
      console.log(key,'::::',obj[key]);
      if (key != 'success' && key != 'fail' && key != 'url' && key != 'form') formData.append(key,obj[key]);
    }
    // formData.append('file',fileInputElement.files[0]);
    // if (UData.userinfo) if (UData.userinfo.openid) param.openid = UData.userinfo.openid;
    // console.log('formData:::',formData.get());
    var url = obj.url ? obj.url : _config.server;
    instance.post(url,formData,config)
      .then((res)=>{
        console.log(res);
        if (res.status == 200) {
          let data = res.data;
          if (obj.success) obj.success(data);
        } else {
          if (obj.fail) obj.fail({errCode: res.status, errMsg: '网络请求失败'});
        }
      })
      .catch((err)=>{
        console.log('axios catch===>',err);
      })
  }

}

export default {
  utils: Utils
}

window.orzzz = Utils;