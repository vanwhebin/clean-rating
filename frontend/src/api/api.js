import api from './index'
import config from '../config/index'
const version = config.version

export const getLatestCampaign = () => {
  return api.get(`${version}/campaign/latest`)
}


export const getCampaignDepts = (campaignID) => {
  return api.get(`${version}/dept/${campaignID}`)
}

export const getScoredRule = (deptID, parameter) => {
  return api.get(`${version}/dept/${deptID}/score`, parameter)
}

export const getDeptRule = (deptID, parameter) => {
  return api.get(`${version}/dept/${deptID}/rule`, parameter)
}


export const postScore = (deptID, data) => {
  return api.post(`${version}/dept/${deptID}`, data)
}

export const putSubmit = (deptID, data) => {
  return api.put(`${version}/dept/${deptID}/status`, data)
}


export const getRanking = (campaignID, parameter) => {
  return api.get(`${version}/campaign/${campaignID}/result`, parameter)
}


export const login = (data) => {
  return api.post(`${version}/login`, data)
}


