import axios from "axios";

const serverUrl = process.env.UNIV_ADMIN_API_URL;
axios.defaults.baseURL = serverUrl;

class Api {
  async login(email, password) {
    try {
      const param = {
        params: {
          email: email,
          password: password,
        },
      };
      const data = await axios.post(`/api/login`, param);
      return data;
    } catch (e) {
      console.log("login error", e);
    }
  }

  async logout(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.post(`/api/logout`);
      return data;
    } catch (e) {
      console.log("logout server error", e);
    }
  }

  async changePassword(userInfo, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const param = {
        params: {
          current_password: userInfo.current_password,
          new_password: userInfo.new_password,
          confirm_password: userInfo.confirm_password,
          email: userInfo.userEmail,
        },
      };
      const data = await axios.post(`/api/change_password`, param);
      return data;
    } catch (e) {
      console.log("change password error", e, e.response);
    }
  }

  async getAllUsers(token, pagination, enabled, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getUsers/${pagination}/${enabled}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("get users error", e);
      return e;
    }
  }

  async setUserDisabled(userId, isEnabled, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const param = {
        params: {
          enabled: isEnabled,
        },
      };
      const data = await axios.post(`/api/updateUserStatus/${userId}`, param);
      return data;
    } catch (e) {
      console.log("set user disable error", e);
      return e;
    }
  }

  async getAllUsersByExamGroup(token, examGroupdId) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/allUsersByExamGroup/${examGroupdId}`);
      return data;
    } catch (e) {
      console.log("get users error", e);
      return e;
    }
  }

  async getQuizzes(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/createExam`);
      return data;
    } catch (e) {
      console.log("getQuiz api", e);
      return e;
    }
  }

  async getAllQuizzes(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/quizzes/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("get all Quiz api error", e);
      return e;
    }
  }

  async getSingleQuizzes(token, id) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getQuizData/${id}`);
      return data;
    } catch (e) {
      console.log("get single quiz api error", e);
      return e;
    }
  }

  async addQuizzes(quizzes, token) {
    const lib_items = [
      "ausculaide",
      "stetho",
      "palpation",
      "ecg",
      "inspection",
      "xray",
      "ucg",
    ];
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: quizzes.title,
        title_en: quizzes.title_en,
        question: quizzes.question,
        question_en: quizzes.question_en,
        case_age: quizzes.case_age,
        case_gender: quizzes.case_gender,
        case: quizzes.current_case,
        case_en: quizzes.current_case_en,
        limit_seconds: quizzes.limit_seconds,
        group_attr: quizzes.group_attribute,
        is_optional: parseInt(quizzes.is_optional),
        exam_group:
          quizzes.selected_exam_group &&
          JSON.stringify(quizzes.selected_exam_group.map((item) => item.id)),
        user_id: quizzes.user_id,
      };
      //library
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "]",
          JSON.stringify(quizzes[`counter_${item}`])
        )
      );
      //library choice
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "_quiz_choices" + "]",
          JSON.stringify(quizzes[`counter_choice_${item}`])
        )
      );
      //library answer explanation / description
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "_description" + "]",
          quizzes[`${item}_description`] == ""
            ? quizzes[`${item}_description`]
            : parseInt(quizzes[`${item}_description`])
        )
      );
      //library word registration
      lib_items.forEach(
        (item) =>
          quizzes[`${item}_fill_in`].key != null &&
          formData.append(
            "params[" + item + "_fill_in" + "]",
            JSON.stringify(quizzes[`${item}_fill_in`])
          )
      );

      //final_answer_quiz_choices
      formData.append(
        "params[" + "final_answer_quiz_choices" + "]",
        JSON.stringify(quizzes.counter_choice_registration)
      );

      //final answer_word_registration
      quizzes.final_fill_in.key != null &&
        formData.append(
          "params[" + "final_fill_in" + "]",
          JSON.stringify(quizzes.final_fill_in)
        );
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }

      formData.append("image_path", quizzes.image_path);
      const data = await axios.post("/api/quizzes", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      console.log("quiz", data);
      return data;
    } catch (e) {
      console.log("add quiz api", e);
      return e;
    }
  }

  async getUserTableSort(info, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      formData.append("params[table]", info.table);
      formData.append("params[id]", info.id);

      const data = await axios.post("/api/getUserSortTable", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("get user sort table api", e);
      return e;
    }
  }

  async updateUserTableSort(userSortData, info, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let disp_order = [];

      if (info.table == "exam management") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, quizPackId: item.quizPackId });
        });
      }
      if (info.table == "quiz pack") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, quizPackId: item.ID });
        });
      }
      if (info.table == "quiz") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, quiz_id: item.id });
        });
      }
      if (info.table == "UCG Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, ucg_id: item.ID });
        });
      }
      if (info.table == "Ausculaide Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, ausculaide_id: item.ID });
        });
      }
      if (info.table == "Palpation Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, palpation_id: item.ID });
        });
      }
      if (info.table == "ECG Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, ecg_id: item.ID });
        });
      }
      if (info.table == "Stetho Sound") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, stetho_id: item.ID });
        });
      }
      if (info.table == "Inspection Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, inspection_id: item.id });
        });
      }
      if (info.table == "X-ray Library") {
        userSortData.map((item, index) => {
          disp_order.push({ disp_order: index, xray_id: item.id });
        });
      }
      formData.append("params[disp_order]", JSON.stringify(disp_order));
      formData.append("params[table]", info.table);
      formData.append("params[id]", info.id);
      formData.append("params[page]", info.page);
      const data = await axios.post("/api/userSortTable", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });

      return data;
    } catch (e) {
      console.log("update user sort table api", e);
      return e;
    }
  }

  async updateQuizzes(quizzes, token) {
    const lib_items = [
      "ausculaide",
      "stetho",
      "palpation",
      "ecg",
      "inspection",
      "xray",
      "ucg",
    ];
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: quizzes.title,
        title_en: quizzes.title_en,
        question: quizzes.question,
        question_en: quizzes.question_en,
        case_age: quizzes.case_age,
        case_gender: quizzes.case_gender,
        case: quizzes.current_case,
        case_en: quizzes.current_case_en,
        limit_seconds: quizzes.limit_seconds,
        group_attr: quizzes.group_attribute,
        user_id: quizzes.user_id,
        is_optional: parseInt(quizzes.is_optional),
        exam_group:
          quizzes.selected_exam_group &&
          JSON.stringify(quizzes.selected_exam_group.map((item) => item.id)),
      };

      //library
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "]",
          JSON.stringify(quizzes[`counter_${item}`])
        )
      );
      //library choice
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "_quiz_choices" + "]",
          JSON.stringify(quizzes[`counter_choice_${item}`])
        )
      );
      //library answer explanation / description
      lib_items.forEach((item) =>
        formData.append(
          "params[" + item + "_description" + "]",
          quizzes[`${item}_description`] == ""
            ? quizzes[`${item}_description`]
            : parseInt(quizzes[`${item}_description`])
        )
      );
      //library word registration
      lib_items.forEach(
        (item) =>
          quizzes[`${item}_fill_in`].key != null &&
          formData.append(
            "params[" + item + "_fill_in" + "]",
            JSON.stringify(quizzes[`${item}_fill_in`])
          )
      );

      //final_answer_quiz_choices
      formData.append(
        "params[" + "final_answer_quiz_choices" + "]",
        JSON.stringify(quizzes.counter_choice_registration)
      );

      //final answer_word_registration
      quizzes.final_fill_in.key != null &&
        formData.append(
          "params[" + "final_fill_in" + "]",
          JSON.stringify(quizzes.final_fill_in)
        );

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }

      if (quizzes.image_path_obj || quizzes.image_path) {
        formData.append(
          "image_path",
          quizzes.image_path_obj ? quizzes.image_path_obj : quizzes.image_path
        );
      }

      const data = await axios.post(`/api/updateQuiz/${quizzes.id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update quiz api", e);
      return e;
    }
  }

  async deleteQuizzes(quizzesId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/quizzes/${quizzesId}`);
      return data;
    } catch (e) {
      console.log("delete quiz api error: ", e);
    }
  }

  async getInspectionLibrary(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/4/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("get inspection library failed", e);
      return e;
    }
  }

  async addInspection(inspectionParams, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: inspectionParams.title,
        title_en: inspectionParams.title_en,
        description: inspectionParams.description,
        description_en: inspectionParams.description_en,
        group_attr: inspectionParams.group_attribute,
        is_normal: inspectionParams.normal_abnormal,
        user_id: inspectionParams.user_id,
        status: parseInt(inspectionParams.status),
        supervisor_comment: inspectionParams.supervisor_comment,
        exam_group:
          inspectionParams.selected_exam_group &&
          JSON.stringify(
            inspectionParams.selected_exam_group.map((item) => item.id)
          ),
        is_video: inspectionParams.is_video,
        lib_type:4
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }
      inspectionParams.sound_file &&
        formData.append("sound_file", inspectionParams.sound_file);
      inspectionParams.video_file &&
        formData.append("lib_video_file", inspectionParams.video_file);
      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("inspection add api", e);
      return e;
    }
  }

  async updateInspection(inspection, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: inspection.title,
        title_en: inspection.title_en,
        description: inspection.description,
        description_en: inspection.description_en,
        group_attr: parseInt(inspection.group_attribute),
        is_normal: parseInt(inspection.normal_abnormal),
        user_id: inspection.user_id,
        status: parseInt(inspection.status),
        supervisor_comment: inspection.supervisor_comment,
        exam_group:
          inspection.selected_exam_group &&
          JSON.stringify(inspection.selected_exam_group.map((item) => item.id)),
        is_video: inspection.is_video,
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      if(inspection.sound_file && _.isString(inspection.sound_file)){
        formData.append(`sound_path`, inspection.sound_file);
      }
      else{
        formData.append(`sound_file`, inspection.sound_file);
      }
      
      if(inspection.video_file && _.isString(inspection.video_file)){
        formData.append(`lib_video_file_path`, inspection.video_file);
      }
      else{
        formData.append(`lib_video_file`, inspection.video_file);
      }
      const data = await axios.post(
        `/api/updateLib/${inspection.id}`,
        formData,
        {
          headers: { "Content-Type": "multipart/form-data" },
        }
      );
      return data;
    } catch (e) {
      console.log("update inspectionLib api", e);
      return e;
    }
  }

  async deleteInspectionLib(inspectionId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${inspectionId}`);
      return data;
    } catch (e) {
      console.log("Inspection Library api error: ", e);
    }
  }

  async getExams(token, pagination, search) {
    try {
      let params = new FormData();
      params.append("search", search);
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getExams/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("getExams api", e);
      return e;
    }
  }

  async addExams(exam, quiz, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        quizzes: JSON.stringify(exam.Step_of_Exam),
        title: quiz.title_jp,
        title_en: quiz.title_en,
        enabled: exam.Enable_Disable,
        quiz_enabled: quiz.is_release,
        description_en: quiz.description_en,
        description: quiz.description,
        icon_path: quiz.icon_path,
        quiz_order_type: quiz.quiz_order_type,
        name_jp: "no jp text field",
        type_exam: exam.type_exam,
        type_quizzes: exam.type_quizzes,
        destination_email: exam.destination_email,
        enabled: exam.exam_release,
        lang: quiz.lang,
        is_public: exam.is_public,
        user_id: exam.user_id,
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }
      formData.append("bg_img", quiz.bg_img);
      const data = await axios.post("/api/createExam", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add exam api", e);
      return e;
    }
  }

  async deleteExam(examId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteExam/${examId}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async updateExams(exam, quiz, token, userId, newExamResult) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();

      let params = {
        quizzes: JSON.stringify(exam.Step_of_Exam),
        title: quiz.title_jp,
        title_en: quiz.title_en,
        enabled: exam.Enable_Disable,
        quiz_enabled: quiz.is_release,
        description_en: quiz.description_en,
        description: quiz.description,
        // icon_path: quiz.icon_path,
        quiz_order_type: quiz.quiz_order_type,
        max_quiz_count: quiz.no_of_questions,
        // color: quiz.title_color,
        name: exam.Title,
        name_jp: exam.Title,
        type_exam: exam.type_exam,
        type_quizzes: exam.type_quizzes,
        destination_email: exam.destination_email,
        enabled: exam.exam_release,
        lang: quiz.lang,
        user_id: userId,
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }
      if (quiz.bg_img != undefined) {
        formData.append("bg_img", quiz.bg_img);
      }
      const data = await axios.post(
        `/api/updateExam/${exam.examId}`,
        formData,
        {
          headers: { "Content-Type": "multipart/form-data" },
        }
      );
      return data;
    } catch (e) {
      console.log("update exam api", e);
      return e;
    }
  }

  async getExamGroups(token) {
    try {
    
      let formData = new FormData();
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get("/api/getExamGroup",  formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("get exam group api", e);
      return e;
    }
  }

  async getExamResults(token, userInfo) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const param = {
        params: {
          user_login_id: userInfo.user_login_id,
          user_univ_id: userInfo.user_univ_id,
          page: userInfo.page,
          user_id: userInfo.user_id,
          exam_id: userInfo.exam_id,
          univ_id: userInfo.univ_id,
          start_date: userInfo.start_date,
          end_date: userInfo.end_date,
          type: userInfo.type,
        },
      };
      const data = await axios.post("/api/getExamlog", param);
      return data;
    } catch (e) {
      console.log("get exam results api", e);
      return e;
    }
  }

  async getChartAnalyticsData(token, userInfo) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const param = {
        params: {
          user_login_id: userInfo.user_login_id,
          user_univ_id: userInfo.user_univ_id,
          chart: userInfo.chart,
          univ_id: userInfo.univ_id,
          user_id: userInfo.user_id,
          type: userInfo.type,
          type_title: userInfo.type_title,
          srt_date: userInfo.srt_date,
          end_date: userInfo.end_date,
        },
      };
      // console.log("params api", param);
      const data = await axios.post("/api/getExamChart", param);
      return data;
    } catch (e) {
      console.log("get chart data api", e);
      return e;
    }
  }

  async getExamAnalyticMenu(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get("/api/getExamAnalyticMenu");
      return data;
    } catch (e) {
      console.log("get exam analaytic menu api", e);
      return e;
    }
  }

  // previews log-analytics
  async getExamResult(examId, examGroupdId, scope, abilty, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(
        `/api/getExamResult/${examId}/${examGroupdId}/${scope}/${abilty}`
      );
      return data;
    } catch (e) {
      console.log("get exam result api", e);
      return e;
    }
  }

  // new log-analytics
  async getLogAnalytics(token, userData) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const param = {
        params: {
          page: userData.page,
          date_from: userData.start_date,
          date_to: userData.end_date,
          exam_type: userData.exam_type ,
          exam_id: userData.exam_id ,
          quiz_id: userData.quiz_id ,
          library_id: userData.library_id,
          user_id: userData.user_id,
          univ_id: userData.univ_id,
        },
      };
      const data = await axios.post("/api/logAnalytics", param);
      return data;
    } catch (e) {
      console.log("get exam result api", e);
      return e;
    }
  }

  async filterLogAnalytics(logInfo, token) {
    axios.defaults.headers.common["Authorization"] = token;
    const param = {
      params: {
        exam_id: logInfo.exam_id,
        univ_id: logInfo.univ_id,
        exam_type: logInfo.exam_type,
        library_id: logInfo.library_id,
        user_login_id: logInfo.user_login_id,
        user_univ_id: logInfo.user_univ_id,
        user_id: logInfo.user_id,
        quiz_id: logInfo.quiz_id,
        start_date: logInfo.start_date,
        end_date: logInfo.end_date,
        type: logInfo.type,
      },
    };
    try {
      const data = await axios.post("/api/getLogAnalytics", param);
      return data;
    } catch (e) {
      console.log("filter log analytics failed", e);
      return e;
    }
  }

  async getRankingData(userInfo, token) {
    axios.defaults.headers.common["Authorization"] = token;
    const param = {
      params: {
        user_id: userInfo.user_id ,
        univ_id: userInfo.univ_id ,
        exam_type: userInfo.exam_type ,
        exam_id: userInfo.exam_id ,
        quiz_id: userInfo.quiz_id ,
        library_id: userInfo.library_id,
        user_login_id: userInfo.user_login_id,
        user_univ_id: userInfo.user_univ_id,
        date_from: userInfo.start_date,
        date_to: userInfo.end_date,
      },
    };
    try {
      const data = await axios.post("/api/getRanking", param);
      return data;
    } catch (e) {
      console.log("get ranking modal data failed", e);
      return e;
    }
  }

  async getPieChartData(userInfo, token) {
    axios.defaults.headers.common["Authorization"] = token;
    const param = {
      params: {
        user_login_id: userInfo.user_login_id,
        user_univ_id: userInfo.user_univ_id,
        date_from: userInfo.start_date,
        date_to: userInfo.end_date,
      },
    };
    try {
      const data = await axios.post("/api/getPieChart", param);
      return data;
    } catch (e) {
      console.log("get pie chart api failed", e);
      return e;
    }
  }

  //reset password
  async resetPassword(email) {
    const param = {
      params: {
        email: email,
      },
    };
    try {
      const data = await axios.post("/api/postEmail", param);
      return data;
    } catch (e) {
      console.log("reset password failed", e);
      return e;
    }
  }

  // change password Password
  async setNewPassword(user) {
    const param = {
      token: user.token,
      password: user.password,
      fromUniv: true,
    };
    try {
      const data = await axios.post("/api/resetPassword", param);
      return data;
    } catch (e) {
      console.log("set new password failed", e);
      return e;
    }
  }

  async getAusculaides(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/1/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed ausculaide", e);
      return e;
    }
  }

  async getAusculaideUser(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get("/api/getAusculaideUser");
      return data;
    } catch (e) {
      console.log("failed ausculaide user", e);
      return e;
    }
  }

  async deleteAusculaide(ausculaideId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${ausculaideId}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async addAusculaides(ausculaide, token) {
    const img_files = ["body_image", "body_image_back"];

    //sound files
    const ve_sound_file = [];
    for (let i = 1; i < 13; i++) {
      ve_sound_file.push(`ve${i}_sound_file`);
    }
    const sound_files = [
      "a_sound_file",
      "pa_sound_file",
      "p_sound_file",
      "pp_sound_file",
      "t_sound_file",
      "pt_sound_file",
      "m_sound_file",
      "pm_sound_file",
      "h1_sound_file",
      "h2_sound_file",
      "h3_sound_file",
      "h4_sound_file",
      "tr1_sound_file",
      "tr2_sound_file",
      "br1_sound_file",
      "br2_sound_file",
      "br3_sound_file",
      "br4_sound_file",
      ...ve_sound_file,
    ];

    //pixel values
    let tracheal_pix_value = this.getStringPixel(2, "tr"); //tr1 , tr2
    let shinon_pix_value = this.getStringPixel(4, "h"); //tr1 , tr2
    let bronchial_pix_value = this.getStringPixel(4, "br");
    let alveolar_pix_value = this.getStringPixel(12, "ve");
    let items = [
      "a",
      "p",
      "t",
      "m",
      ...tracheal_pix_value,
      ...shinon_pix_value,
      ...bronchial_pix_value,
      ...alveolar_pix_value,
    ];
    let pixels = ["x", "y", "r"];
    let item_pixels = [];
    let pix_obj = {};

    for (let i = 0; i < items.length; i++) {
      for (let j = 0; j < pixels.length; j++) {
        item_pixels.push(`${items[i]}_${pixels[j]}`);
      }
    }

    item_pixels.forEach(
      (item) =>
        (pix_obj = {
          ...pix_obj,
          [item]: ausculaide[item],
        })
    );

    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        user_id: ausculaide.user_id,
        title: ausculaide.title,
        title_en: ausculaide.title_en,
        description: ausculaide.description,
        description_en: ausculaide.description_en,
        img_description: ausculaide.image_description_ja,
        img_description_en: ausculaide.image_description_en,
        status: parseInt(ausculaide.status),
        sort: parseInt(ausculaide.sort),
        is_normal: parseInt(ausculaide.normal_abnormal),
        group_attr: parseInt(ausculaide.group_attribute),
        coordinate: ausculaide.coordinate,
        content_group: parseInt(ausculaide.selected_content_group),
        supervisor_comment: ausculaide.supervisor_comment,
        ...pix_obj,
        exam_group: JSON.stringify(
          ausculaide.selected_exam_group.map((item) => item.id)
        ),
        lib_type:1,
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      //explain image
      //jp
      ausculaide.explanatory_image_ja.length != 0 &&
        ausculaide.explanatory_image_ja.map((item, index) => {
          item.image &&
            formData.append(`sound_img[` + index + `]`, item.image);
        });

      //en
      ausculaide.explanatory_image_en.length != 0 &&
        ausculaide.explanatory_image_en.map((item, index) => {
          item.image &&
            formData.append(`sound_img_en[` + index + `]`, item.image);
        });

      formData.append("body_image_file", ausculaide["body_image"])
      formData.append("body_image_back_file", ausculaide["body_image_back"])

      sound_files.forEach(
        (file) =>
          ausculaide[file] && formData.append(`${file}`, ausculaide[file])
      );

      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add ausculaide api", e);
      return e;
    }
  }

  getStringPixel(count, type) {
    let arr = [];
    for (let i = 1; i <= count; i++) {
      arr.push(`${type}${i}`);
    }
    return arr;
  }
  async updateAusculaideUrl(url, token, id) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      formData.append("moodle_url", url);
      const data = await axios.post(`/api/updateAusculaideUrl/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    }catch (e) {
      console.log("update ausculaide url", e);
      return e;
    }
  }
  
  async updateAusculaide(ausculaide, token, id) {
    //sound files
    const ve_sound_file = [];
    for (let i = 1; i < 13; i++) {
      ve_sound_file.push(`ve${i}_sound_file`);
    }
    
    const sound_files = [
      "a_sound_file",
      "pa_sound_file",
      "p_sound_file",
      "pp_sound_file",
      "t_sound_file",
      "pt_sound_file",
      "m_sound_file",
      "pm_sound_file",
      "h1_sound_file",
      "h2_sound_file",
      "h3_sound_file",
      "h4_sound_file",
      "tr1_sound_file",
      "tr2_sound_file",
      "br1_sound_file",
      "br2_sound_file",
      "br3_sound_file",
      "br4_sound_file",
      ...ve_sound_file,
    ];
    console.log("sound_files",sound_files)
    //pixel values
    let tracheal_pix_value = this.getStringPixel(2, "tr"); //tr1 , tr2
    let shinon_pix_value = this.getStringPixel(4, "h"); //tr1 , tr2
    let bronchial_pix_value = this.getStringPixel(4, "br");
    let alveolar_pix_value = this.getStringPixel(12, "ve");
    let items = [
      "a",
      "p",
      "t",
      "m",
      ...tracheal_pix_value,
      ...shinon_pix_value,
      ...bronchial_pix_value,
      ...alveolar_pix_value,
    ];
    let pixels = ["x", "y", "r"];
    let item_pixels = [];
    let pix_obj = {};

    for (let i = 0; i < items.length; i++) {
      for (let j = 0; j < pixels.length; j++) {
        item_pixels.push(`${items[i]}_${pixels[j]}`);
      }
    }

    item_pixels.forEach(
      (item) =>
        (pix_obj = {
          ...pix_obj,
          [item]: ausculaide[item],
        })
    );

    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();

      let params = {
        //params
        user_id: ausculaide.user_id,
        title: ausculaide.title,
        title_en: ausculaide.title_en,
        description: ausculaide.description,
        description_en: ausculaide.description_en,
        img_description: ausculaide.image_description_ja,
        img_description_en: ausculaide.image_description_en,
        status: parseInt(ausculaide.status),
        sort: parseInt(ausculaide.sort),
        is_normal: parseInt(ausculaide.normal_abnormal),
        group_attr: parseInt(ausculaide.group_attribute),
        coordinate: ausculaide.coordinate,
        content_group: parseInt(ausculaide.content_group),
        supervisor_comment: ausculaide.supervisor_comment,
        exam_group: JSON.stringify(
          ausculaide.selected_exam_group.map((item) => item.id)
        ),
        lib_type:1,
        ...pix_obj,
      };

      //explain image
      //explain image edit ja
      ausculaide.explanatory_image_ja.length != 0 &&
        ausculaide.explanatory_image_ja.map((item, index) => {
          item.image_path &&
            formData.append(`sound_img_id[` + index + `]`, item.id);
        });

      //explain image edit en
      ausculaide.explanatory_image_en.length != 0 &&
        ausculaide.explanatory_image_en.map((item, index) => {
          item.image_path &&
            formData.append(`sound_img_id_en[` + index + `]`, item.id);
        });

      //jp
      ausculaide.explanatory_image_ja.length != 0 &&
        ausculaide.explanatory_image_ja.map((item, index) => {
          item.image_path &&
            formData.append(
              `sound_img[` + index + `]`,
              item.image_path
            );
        });

      //en
      ausculaide.explanatory_image_en.length != 0 &&
        ausculaide.explanatory_image_en.map((item, index) => {
          item.image_path &&
            formData.append(
              `sound_img_en[` + index + `]`,
              item.image_path
            );
        });

      //remove ids
      formData.append(
        `remove_sound_img_id`,
        JSON.stringify(ausculaide.remove_explain_img_id)
      );

      formData.append("body_image_file", ausculaide["body_image"])
      formData.append("body_image_back_file", ausculaide["body_image_back"])

      sound_files.forEach(
        (file) =>{
          if(ausculaide[file] && _.isString(ausculaide[file])){
            formData.append(`${file.replace("_file", "_path")}`, ausculaide[file])
          }
          else{
            formData.append(`${file}`, ausculaide[file])
          }
        }

      );

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      // Display the key/value pairs
      // for (var pair of formData.entries()) {
      //   console.log(pair[0] + ", " + pair[1]);
      // }
      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update ausculaide api", e);
      return e;
    }
  }

  async getLibraryUser(token) {
    try {
      let formData = new FormData();
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get("/api/getLibraryUser",  formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("failed library user", e);
      return e;
    }
  }

  async getEcg(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/3/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed ECG", e);
      return e;
    }
  }

  async addEcg(ecg, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        user_id: ecg.user_id,
        title: ecg.title,
        title_en: ecg.title_en,
        description: ecg.ecg_explanation,
        description_en: ecg.ecg_explanation_en,
        status: parseInt(ecg.status),
        is_normal: parseInt(ecg.normal_abnormal),
        //group_attr: parseInt(ecg.group_attribute),
        lib_type:3,
        // exam_group: JSON.stringify(
        //   ecg.selected_exam_group.map((item) => item.id)
        // ),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }
      formData.append("lib_image_file", ecg.image_file);
      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add addEcgLib api", e);
      return e;
    }
  }

  async updateEcg(id, ecg, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        user_id: ecg.user_id,
        title: ecg.title,
        title_en: ecg.title_en,
        description: ecg.ecg_explanation,
        description_en: ecg.ecg_explanation_en,
        status: parseInt(ecg.status),
        is_normal: parseInt(ecg.normal_abnormal),
        // group_attr: parseInt(ecg.group_attribute),
        // exam_group: JSON.stringify(
        //   ecg.selected_exam_group.map((item) => item.id)
        // ),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      ecg.image_path && formData.append("lib_image_file_path", ecg.image_path);

      ecg.image_file &&
        formData.append("lib_image_file", ecg.image_file);

      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update EcgLib api", e);
      return e;
    }
  }

  async deleteEcg(ecgId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${ecgId}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async getQuizPacks(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getQuizPacks/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed quizpacks", e);
      return e;
    }
  }

  async getQuizPacksIndex(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get("/api/getQuizPacksIndex");
      return data;
    } catch (e) {
      console.log("failed quizpacks Index", e);
      return e;
    }
  }

  async getSingleQuizPack(token, id, lang) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/quizStart/${id}?params[lang]=${lang}`);
      return data;
    } catch (e) {
      console.log("failed get single quizpack", e);
      return e;
    }
  }

  async deleteQuizPack(quizPackId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteQuizPack/${quizPackId}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async addQuizPack(quiz, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: quiz.title,
        title_en: quiz.title_en,
        color: quiz.color,
        description_en: quiz.description_en,
        description: quiz.description,
        quiz_order_type: quiz.question_format,
        is_public: parseInt(quiz.release),
        lang: quiz.lang,
        group_attr: parseInt(quiz.group_attribute),
        quizzes: JSON.stringify(quiz.added_quiz),
        exam_group: JSON.stringify(
          quiz.selected_exam_group.map((item) => item.id)
        ),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }
      formData.append("bg_img", quiz.icon);
      const data = await axios.post("/api/addQuizPack", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add quiz api", e);
    }
  }

  async updateQuizPack(quiz, token, id) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        title: quiz.title,
        title_en: quiz.title_en,
        color: quiz.color,
        description_en: quiz.description_en,
        description: quiz.description,
        quiz_order_type: quiz.question_format,
        is_public: parseInt(quiz.release),
        lang: quiz.lang,
        group_attr: parseInt(quiz.group_attribute),
        quizzes: JSON.stringify(quiz.added_quiz),
        exam_group: JSON.stringify(
          quiz.selected_exam_group.map((item) => item.id)
        ),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append("params[" + keys[i] + "]", values[i]);
      }

      quiz.new_icon != undefined && formData.append("bg_img", quiz.new_icon);

      const data = await axios.post(`/api/updateQuizPack/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update quiz api", e);
      return e;
    }
  }

  /**
   * get xray library
   * @param {*} token
   * @param {*} pagination
   */
  async getXrays(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/5/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed xray", e);
      return e;
    }
  }

  /**
   * add xray
   * @param {*} xray
   * @param {*} token
   */
  async addXray(xray, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        user_id: xray.user_id,
        title: xray.title,
        title_en: xray.title_en,
        description: xray.xray_explanation,
        description_en: xray.xray_explanation_en,
        status: parseInt(xray.status),
        is_normal: parseInt(xray.normal_abnormal),
        group_attr: parseInt(xray.group_attribute),
        supervisor_comment: xray.supervisor_comment,
        lib_type:5
        // exam_group: JSON.stringify(
        //   xray.selected_exam_group.map((item) => item.id)
        // ),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      formData.append("lib_image_file", xray.image_file);
      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add xray api", e);
      return e;
    }
  }

  /**
   * update xray
   * @param {*} xray
   * @param {*} token
   * @param {*} id
   * @param {*} newXrayResult
   */
  async updateXray(xray, token, id) {
    // console.log(xray);
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();

      let params = {
        //params
        user_id: xray.user_id,
        title: xray.title,
        title_en: xray.title_en,
        description: xray.description,
        description_en: xray.description_en,
        status: parseInt(xray.status),
        is_normal: parseInt(xray.is_normal),
        group_attr: parseInt(xray.group_attr),
        supervisor_comment: xray.supervisor_comment,
        // exam_group:
        //   xray.selected_exam_group &&
        //   JSON.stringify(xray.selected_exam_group.map((item) => item.id)),
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }
      
      // if (xray.image_file_object || xray.image_file) {
      //   formData.append(
      //     "image_path",
      //     xray.image_file_object ? xray.image_file_object : xray.image_file
      //   );
      // }
      if(xray.image_file && _.isString(xray.image_file)){
        formData.append(`lib_image_file_path`, xray.image_file);
      }
      else{
        formData.append(`lib_image_file`, xray.image_file);
      }

      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update xray api", e);
      return e;
    }
  }

  /**
   * delete xray
   * @param {*} xrayID
   * @param {*} token
   */
  async deleteXray(xrayID, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${xrayID}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async getStetho(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/0/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed stetho", e);
      return e;
    }
  }

  async deleteStetho(id, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${id}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async addStetho(stetho, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        //sound source file remaining
        is_video: stetho.is_video,
        title: stetho.title,
        title_en: stetho.title_en,
        sound_type: parseInt(stetho.selected_sound_type),
        ausculation_site: stetho.ausculation_site,
        ausculation_site_en: stetho.ausculation_site_en,
        conversion_type: parseInt(stetho.selected_conversion_type),
        is_normal: parseInt(stetho.normal_abnormal),
        disease: stetho.disease,
        disease_en: stetho.disease_en,
        sub_description: stetho.source_desc,
        sub_description_en: stetho.source_desc_en,
        description: stetho.description,
        description_en: stetho.description_en,
        user_id: stetho.user_id,
        supervisor_comment: stetho.supervisor_comment,
        status: parseInt(stetho.status),
        lib_type: 0,

        group_attr: parseInt(stetho.group_attribute),
        exam_group: JSON.stringify(
          stetho.selected_exam_group.map((item) => item.id)
        ),

        //sound file description image
        // image_titles: JSON.stringify(stetho.sound_img_desc),
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }
      //jp
      //soundfile image add
      stetho.image_list.length != 0 &&
        stetho.image_list.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img[` + index + `]`, file.sound_img);
        });

      //sound file description image
      stetho.image_list.length != 0 &&
        stetho.image_list.map((file, index) => {
          file.sound_img &&
            formData.append(
              `sound_img_desc[` + index + `]`,
              file.sound_img_desc
            );
        });

      //en
      //soundfile image add
      stetho.image_list_en.length != 0 &&
        stetho.image_list_en.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img_en[` + index + `]`, file.sound_img);
        });

      //sound file description image
      stetho.image_list_en.length != 0 &&
        stetho.image_list_en.map((file, index) => {
          file.sound_img &&
            formData.append(
              `sound_img_desc_en[` + index + `]`,
              file.sound_img_desc
            );
        });

      stetho.sound_source && formData.append(`sound_file`, stetho.sound_source);

      // Display the key/value pairs
      // for (var pair of formData.entries()) {
      //   console.log(pair[0] + ", " + pair[1]);
      // }

      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add Stetho error api", e);
      return e;
    }
  }

  async updateStetho(stetho, token, id) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();

      let params = {
        is_video: stetho.is_video,
        title: stetho.title,
        title_en: stetho.title_en,
        sound_type: parseInt(stetho.selected_sound_type),
        ausculation_site: stetho.ausculation_site,
        ausculation_site_en: stetho.ausculation_site_en,
        conversion_type: parseInt(stetho.selected_conversion_type),
        is_normal: parseInt(stetho.normal_abnormal),
        disease: stetho.disease,
        disease_en: stetho.disease_en,
        sub_description: stetho.source_desc,
        sub_description_en: stetho.source_desc_en,
        description: stetho.description,
        description_en: stetho.description_en,
        user_id: stetho.user_id,
        supervisor_comment: stetho.supervisor_comment,
        status: parseInt(stetho.status),

        group_attr: parseInt(stetho.group_attribute),
        exam_group: JSON.stringify(
          stetho.selected_exam_group.map((item) => item.id)
        ),
        //sound file description image
        // image_files: JSON.stringify(stetho.sound_img),
        // image_titles: JSON.stringify(stetho.sound_img_desc),
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      //soundfile image edit ja
      stetho.image_list.length != 0 &&
        stetho.image_list.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img_id[` + index + `]`, file.id);
        });

      //soundfile image edit en
      stetho.image_list_en.length != 0 &&
        stetho.image_list_en.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img_id_en[` + index + `]`, file.id);
        });

      //--------ja--------
      //soundfile image add
      stetho.image_list.length != 0 &&
        stetho.image_list.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img[` + index + `]`, file.sound_img);
        });

      //sound file description image
      stetho.image_list.length != 0 &&
        stetho.image_list.map((file, index) => {
          file.sound_img &&
            formData.append(
              `sound_img_desc[` + index + `]`,
              file.sound_img_desc
            );
        });

      //--------en--------
      //soundfile image add
      stetho.image_list_en.length != 0 &&
        stetho.image_list_en.map((file, index) => {
          file.sound_img &&
            formData.append(`sound_img_en[` + index + `]`, file.sound_img);
        });

      //sound file description image
      stetho.image_list_en.length != 0 &&
        stetho.image_list_en.map((file, index) => {
          file.sound_img &&
            formData.append(
              `sound_img_desc_en[` + index + `]`,
              file.sound_img_desc
            );
        });

      //remove ids
      formData.append(
        `remove_sound_img_id`,
        JSON.stringify(stetho.remove_sound_img_id)
      );

      // Display the key/value pairs
      // for (var pair of formData.entries()) {
      //   console.log(pair[0] + ", " + pair[1]);
      // }
      
      if(stetho.sound_source && _.isString(stetho.sound_source)){
        formData.append(`sound_path`, stetho.sound_source);
      }
      else{
        formData.append(`sound_file`, stetho.sound_source);
      }

      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update stetho api", e);
      return e;
    }
  }

  async getUcgLibrary(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/6/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("ucg api failed", e);
      return e;
    }
  }

  async addUcgLibrary(token, ucgData) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        user_id: ucgData.user_id,
        title: ucgData.title,
        title_en: ucgData.title_en,
        description: ucgData.ucg_explanation,
        description_en: ucgData.ucg_explanation_en,
        status: ucgData.status,
        is_normal: ucgData.normal_abnormal,
        supervisor_comment: ucgData.supervisor_comment,
        group_attr: ucgData.group_attribute,
        exam_group: JSON.stringify(
          ucgData.selected_exam_group.map((item) => item.id)
        ),
        lib_type:6
      };
      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      ucgData.video_file &&
        formData.append("lib_video_file", ucgData.video_file);

      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("add ucg api error", e);
      return e;
    }
  }

  async deleteUcg(ucgId, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${ucgId}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

  async updateUcg(id, ucgData, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        user_id: ucgData.user_id,
        title: ucgData.title,
        title_en: ucgData.title_en,
        description: ucgData.ucg_explanation,
        description_en: ucgData.ucg_explanation_en,
        status: ucgData.status,
        is_normal: ucgData.normal_abnormal,
        supervisor_comment: ucgData.supervisor_comment,
        group_attr: ucgData.group_attribute,
        exam_group:
          ucgData.selected_exam_group &&
          JSON.stringify(ucgData.selected_exam_group.map((item) => item.id)),
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      if(ucgData.video_file && _.isString(ucgData.video_file)){
        formData.append("lib_video_file_path", ucgData.video_file);
      }
      else{
        formData.append("lib_video_file", ucgData.video_file);
      }

      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update Ucg Lib api", e);
      return e;
    }
  }

  //palpation
  async getPalpation(token, pagination, search) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getLib/2/${pagination}?params[search]=${search}`);
      return data;
    } catch (e) {
      console.log("failed palpation", e);
      return e;
    }
  }

  async addPalpation(palpation, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        user_id: palpation.user_id,
        is_video: palpation.is_video,
        title: palpation.title,
        title_en: palpation.title_en,
        description: palpation.description,
        description_en: palpation.description_en,

        status: parseInt(palpation.status),
        is_normal: parseInt(palpation.normal_abnormal),
        group_attr: parseInt(palpation.group_attribute),
        supervisor_comment: palpation.supervisor_comment,

        exam_group: JSON.stringify(
          palpation.selected_exam_group.map((item) => item.id)
        ),
        lib_type:2
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      palpation.sound_file &&
        formData.append(`sound_file`, palpation.sound_file);
      palpation.video_file &&
        formData.append(`lib_video_file`, palpation.video_file);

      const data = await axios.post("/api/addLib", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log(" addPalpationLib api", e);
      return e;
    }
  }

  async getSelectMenus(token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      const data = await axios.post("/api/analyticMenu", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log(" getSelectMenus api", e);
      return e;
    }
  }

  async updatePalpation(palpation, token, id) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      let formData = new FormData();
      let params = {
        //params
        user_id: palpation.user_id,
        is_video: palpation.is_video,
        title: palpation.title,
        title_en: palpation.title_en,
        description: palpation.description,
        description_en: palpation.description_en,

        status: parseInt(palpation.status),
        is_normal: parseInt(palpation.normal_abnormal),
        group_attr: parseInt(palpation.group_attribute),
        supervisor_comment: palpation.supervisor_comment,

        exam_group:
          palpation.selected_exam_group &&
          JSON.stringify(palpation.selected_exam_group.map((item) => item.id)),
      };

      let keys = Object.keys(params);
      let values = Object.values(params);
      for (let i = 0; i < values.length; i++) {
        formData.append(keys[i], values[i]);
      }

      if(palpation.sound_file && _.isString(palpation.sound_file)){
        formData.append(`sound_path`, palpation.sound_file);
      }
      else{
        formData.append(`sound_file`, palpation.sound_file);
      }

      if(palpation.video_file && _.isString(palpation.video_file)){
        formData.append(`lib_video_file_path`, palpation.video_file);
      }
      else{
        formData.append(`lib_video_file`, palpation.video_file);
      }

      const data = await axios.post(`/api/updateLib/${id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      return data;
    } catch (e) {
      console.log("update PalpationLib api", e);
      return e;
    }
  }

  async deletePalpation(id, token) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const { data } = await axios.delete(`/api/deleteLibItem/${id}`);
      return data;
    } catch (e) {
      console.log(e);
    }
  }

   /**
   * get HOME data
   * @param {*} token
   * @param {*} pagination
   */
  async getHomeData(token, pagination) {
    try {
      axios.defaults.headers.common["Authorization"] = token;
      const data = await axios.get(`/api/getManagementInformation/${pagination}`);
      console.log(data);
      return data;
    } catch (e) {
      console.log("failed home", e);
      return e;
    }
  }

}


export default new Api();
