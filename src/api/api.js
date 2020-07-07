import api from './index'
const version = 'api/v1'

// 提交项目表单
export const postProgram = (data) => {
  return api.post(`${version}/program`, data)
}

export const postScore = (programID, data) => {
  return api.put(`${version}/program/${programID}`, data)
}

export const getUsers = () => {
  return api.get(`${version}/user`)
}
export const getProgramList = (campaignID) => {
  return api.get(`${version}/campaign/${campaignID}`)
}

export const postCampaignVote = (campaignUID) => {
  return api.put(`${version}/campaign/${campaignUID}/vote`)
}

export const getRanking = (campaignUID) => {
  return api.get(`${version}/campaign/${campaignUID}/result`)
}


