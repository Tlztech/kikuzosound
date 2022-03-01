import axios from "axios";
const CSRF_TOKEN = sessionStorage.getItem("CSRF_TOKEN") || '';

export async function get(url, params) {
  const promise = await axios.get(url, {
    headers: {
      'Authorization' : CSRF_TOKEN
    },
    params: params,
  });
  return promise;
}

export async function post(url, params) {
  const header = url.includes("login")
    ? {}
    : {
        headers: {
          'Authorization' : CSRF_TOKEN
        },
      };

  const promise = await axios.post(url, params, header);
  return promise;
}

// export async function put(url, params, context) {
//   axios.interceptors.response.use((response) => response, tokenError);

//   const header =
//     context && context.props.user.userData.token
//       ? {
//           headers: {
//             Authorization: "Bearer " + context.props.user.userData.token,
//           },
//         }
//       : {};

//   const promise = await axios.put(url, params, header);
//   return promise;
// }

export async function getQuizList() {
  return await get(process.env.UNIV_ADMIN_API_URL + "/api/createExam", {});
}

export async function getExams() {
 return await get(process.env.UNIV_ADMIN_API_URL + "/api/getExams", {});
}

export async function login(email, password) {
  let params = new URLSearchParams();
  params.append("params[email]", email);
  params.append("params[password]", password);
  return await post(process.env.UNIV_ADMIN_API_URL + "/api/login", params);
}
