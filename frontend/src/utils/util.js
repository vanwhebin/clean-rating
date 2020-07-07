export function timeStampFormat (ts, type=null) {
    ts = ts.toString().length === 10 ? ts * 1000 : ts
    const dateObject = new Date(ts)
    let hour = dateObject.getHours()
    let min = dateObject.getMinutes()
    let sec = dateObject.getSeconds()
    hour = hour > 10 ? hour : '0' + hour.toString()
    min = min > 10 ? min : '0' + min.toString()
    sec = sec > 10 ? sec : '0' + sec.toString()
    const date = dateObject.getFullYear() + '-' + dateObject.getMonth() + '-' + dateObject.getDay()
    const time = hour + ':' + min + ':' + sec
    if (type === 'date') {
        return date
    } else if (type === 'time') {
        return time
    } else {
        return (date + ' ' + time)
    }
}
