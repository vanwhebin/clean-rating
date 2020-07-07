/**
 * 存储localStorage
 */
export const setStore = (name, content, expire = '') => {
    if (!name) return
    // if (typeof content !== 'string') {
    //   content = JSON.stringify(content)
    // }

    expire = expire ? expire : (3600 * 1000 * 6)

    let obj = {
        name: name,
        value: content,
        expire: expire,
        startTime: (new Date()).getTime()//记录何时将值存入缓存，毫秒级
    }
    let options = {}
    //将obj和传进来的params合并
    Object.assign(options, obj)
    console.log(options)
    if (options.expire) {
        //如果options.expires设置了的话
        //以options.name为key，options为值放进去
        localStorage.setItem(options.name, JSON.stringify(options))
    } else {
        //如果options.expires没有设置，就判断一下value的类型
        // let type = Object.prototype.toString.call(options.value)
        //如果value是对象或者数组对象的类型，就先用JSON.stringify转一下，再存进去
        if (Object.prototype.toString.call(options.value) === '[object Object]') {
            options.value = JSON.stringify(options.value)
        }
        if (Object.prototype.toString.call(options.value) === '[object Array]') {
            options.value = JSON.stringify(options.value)
        }
        localStorage.setItem(options.name, options.value)
    }
}
/**
 * 获取localStorage
 */
export const getStore = name => {
    if (!name) return

    let item = localStorage.getItem(name)
    //先将拿到的试着进行json转为对象的形式
    try {
        item = JSON.parse(item)
    } catch (error) {
        //如果不行就不是json的字符串，就直接返回
        // item = item;
        return item
    }
    //如果有startTime的值，说明设置了失效时间
    if (item && item.startTime) {
        let date = (new Date()).getTime()
        //何时将值取出减去刚存入的时间，与item.expires比较，如果大于就是过期了，如果小于或等于就还没过期
        if (date - item.startTime > item.expire) {
            //缓存过期，清除缓存，返回false
            localStorage.removeItem(name)
            return false
        } else {
            //缓存未过期，返回值
            return item.value
        }
    } else {
        //如果没有设置失效时间，直接返回值
        return item
    }
}
/**
 * 删除localStorage
 */
export const removeStore = name => {
    if (!name) return
    window.sessionStorage.removeItem(name)
}
