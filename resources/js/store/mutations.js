let mutations = {

    //Create Agent Mutation
    CREATE_AGENT(state, agent)
    {
        state.agents.unshift(agent)
    },

    //GET AGENT FROM STATE
    FETCH_AGENTS(state, agent)
    {
        return state.agents = agent
    },

    // Delete Agent Mutation
    DELETE_AGENT(state, agent)
    {
        let index = state.posts.findIndex(item => item.id === agent.id)
        state.agents.splice(index, 1)
    }
}

export default mutations
