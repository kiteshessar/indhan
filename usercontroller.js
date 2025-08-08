var { sequelize, user: userModel } = require("../model/index");
var jwt = require("jsonwebtoken");
var path = require("path");
const env = require("../config/env");
var config = require(path.join(__dirname, "..", "config", "config.json"))[env];
const LoginHistoryController = require("./loginHistoryController");
const { sha1Encrypt, generateSmsUrl } = require("../shared/helper");
const { default: axios } = require("axios");

module.exports.createUser = function (req, res) {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.user_name) {
    return res.status(400).json({
      status: false,
      message: "username required",
    });
  }
  if (!req.body.user_phone) {
    return res.status(400).json({
      status: false,
      message: "mobile number required",
    });
  }
  if (!req.body.user_type) {
    return res.status(400).json({
      status: false,
      message: "user role is required",
    });
  }
  // let password = req.body.password;
  // // check this, because UI will pass password in encrypted format
  // if (!req.body.createUsing || req.body.createUsing != "UI") {
  //   password = sha1Encrypt(req.body.password);
  // }
  var userBody = {
    name: req.body.name,
    user_name: req.body.user_name,
    user_email: req.body.user_email,
    user_phone: req.body.user_phone,
    // password: password,
    user_type: req.body.user_type,
    is_active: req.body.is_active,
  };
  userModel
    .findOne({
      where: {
        user_phone: userBody.user_phone,
      },
    })
    .then((userData) => {
      if (userData) {
        return res.status(400).json({
          status: false,
          message: "User already exists",
        });
      } else {
        userModel.create(userBody).then((response) => {
          console.log("create data response - ", response);
          res.status(200).json({
            status: true,
            message: "User created",
          });
          return;
        });
      }
    });
};

module.exports.login = function (req, res) {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.user_email) {
    return res.status(400).json({
      status: false,
      message: "user_email required",
    });
  }
  if (!req.body.password) {
    return res.status(400).json({
      status: false,
      message: "passsword required",
    });
  }
  userModel
    .findOne({
      where: {
        user_email: req.body.user_email,
      },
    })
    .then((userData) => {
      if (userData) {
        // block admin user to login in app
        if (
          userData.user_type === "admin" &&
          req.body.deviceType === "mobile"
        ) {
          res.status(400).json({
            status: false,
            message: "Invalid user",
          });
        }
        // block user to login in web
        if (userData.user_type === "user" && req.body.deviceType === "web") {
          res.status(400).json({
            status: false,
            message: "Invalid user",
          });
        }

        let password = req.body.password;
        // check this, because UI will pass password in encrypted format
        if (!req.body.createUsing || req.body.createUsing != "UI") {
          password = sha1Encrypt(req.body.password);
        }
        // console.log("userData ", userData.dataValues);
        if (password == userData.password) {
          // if (userData.is_loggedin) {
          //   return res.status(403).json({
          //     status: false,
          //     message: "User already loggedin",
          //   });
          // }
          if (!userData.is_active) {
            return res.status(403).json({
              status: false,
              message: "User inactive, please contact admin",
            });
          }

          var generatedToken = jwt.sign(
            {
              id: userData.dataValues.id,
              username: req.body.username,
              user_phone: userData.user_phone,
              user_email: userData.user_email,
              last_login: req.lastLogin,
              user_type: userData.user_type,
            },
            config.jwtSecret,
            {
              expiresIn: "1d",
            }
          );
          LoginHistoryController.loginEntry(
            userData.id,
            req.body.username,
            req.body.phone_number,
            userData.user_email,
            req.body.usertype ? req.body.usertype : "user"
          );
          userModel
            .update(
              {
                is_loggedin: 1,
              },
              {
                where: {
                  id: userData.id,
                },
              }
            )
            .then((updateUserRes) => {
              if (!updateUserRes) {
                return res.status(200).json({
                  status: false,
                  message: "Please try again after sometime",
                });
              }
              return res.status(200).json({
                status: true,
                message: "Login success",
                data: {
                  token: generatedToken,
                },
              });
            })
            .catch((updateUserErr) => {
              console.log("======== updateUserErr ========");
              console.log(updateUserErr);
              return res.status(200).json({
                status: false,
                message: "Please try again after sometime",
              });
            });
        } else {
          res.status(403).json({
            status: false,
            message: "Invalid username or password",
          });
        }
      } else {
        res.status(403).json({
          status: false,
          message: "Invalid username or password",
        });
      }
    });
};

module.exports.verifyUser = function (req, res) {
  // console.log("decoded token: " + JSON.stringify(req.decodedToken));
  userModel
    .findOne({
      where: {
        username: req.decodedToken.username,
      },
    })
    .then((userData) => {
      if (userData) {
        res.status(200).json({
          status: true,
          message: "Valid user",
        });
      } else {
        res.status(401).json({
          status: true,
          message: "Invalid user",
        });
      }
    });
};

module.exports.changePassword = function (req, res) {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.old_password) {
    return res.status(400).json({
      status: false,
      message: "Old password required",
    });
  }
  if (!req.body.new_password) {
    return res.status(400).json({
      status: false,
      message: "New password required",
    });
  }
  userModel
    .findOne({
      where: {
        id: req.decodedToken.id,
      },
    })
    .then((userData) => {
      if (!userData) {
        return res.status(404).json({
          status: false,
          message: "User not found, please contact admin",
        });
      }

      let old_password = req.body.old_password;
      // check this, because UI will pass password in encrypted format
      if (!req.body.createUsing || req.body.createUsing != "UI") {
        old_password = sha1Encrypt(req.body.old_password);
      }
      if (userData.password !== old_password) {
        return res.status(400).json({
          status: false,
          message: "Incorrect old password",
        });
      }

      // check this, because UI will pass password in encrypted format
      let new_password = req.body.new_password;
      if (!req.body.createUsing || req.body.createUsing != "UI") {
        new_password = sha1Encrypt(req.body.new_password);
      }
      userModel
        .update(
          {
            password: new_password,
          },
          {
            where: {
              id: req.decodedToken.id,
            },
          }
        )
        .then((updateUserRes) => {
          if (!updateUserRes) {
            return res.status(200).json({
              status: false,
              message: "Please try again after sometime",
            });
          }
          return res.status(200).json({
            status: true,
            message: "Password updated",
          });
        })
        .catch((updateDealerErr) => {
          console.log("======== updateDealerErr ========");
          console.log(updateDealerErr);
        });
    });
};

module.exports.logout = (req, res) => {
  let userId = req.decodedToken.id;
  if (!req.params.id) {
    res.status(400).json({
      status: 0,
      message: "Invalid request",
    });
  }
  userModel
    .update(
      {
        is_loggedin: 0,
      },
      {
        where: {
          id: req.params.id,
        },
      }
    )
    .then((logoutRes) => {
      res.status(200).json({
        status: true,
        message: "Logout successful",
      });
    });
};

module.exports.deleteUser = (req, res) => {
  if (!req.params.id) {
    return res.status(400).json({
      status: false,
      message: "User id is required",
    });
  }
  if (
    !req.decodedToken.user_type ||
    req.decodedToken.user_type.toLowerCase() !== "admin"
  ) {
    return res.status(400).json({
      status: false,
      message: "You are not allowed to delete a lead",
    });
  }
  userModel
    .destroy({
      where: {
        id: req.params.id,
      },
    })
    .then((userRemoveRes) => {
      return res.status(200).json({
        status: true,
        message: "User deleted successfully",
      });
    })
    .catch((userRemoveErr) => {
      console.log("===== userRemoveErr =====", userRemoveErr);
      return res.status(500).json({
        status: false,
        message: "Please try again later",
      });
    });
};

module.exports.editUser = (req, res) => {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.id) {
    return res.status(400).json({
      status: false,
      message: "User id required",
    });
  }
  let columnsToUpdate = {};
  if (req.body.name) {
    columnsToUpdate["name"] = req.body.name;
  }
  if (req.body.user_email) {
    columnsToUpdate["user_email"] = req.body.user_email;
  }
  if (req.body.user_phone) {
    columnsToUpdate["user_phone"] = req.body.user_phone;
  }
  // if (req.body.password) {
  //   // check this, because UI will pass password in encrypted format
  //   if (!req.body.updateUsing || req.body.updateUsing != "UI") {
  //     columnsToUpdate["password"] = sha1Encrypt(req.body.password);
  //   } else {
  //     columnsToUpdate["password"] = req.body.password;
  //   }
  // }
  if (req.body.user_type) {
    // let allowedUserType = ["user", "admin"];
    // if (!allowedUserType.includes(req.body.user_type.toLowerCase())) {
    //   return res.status(400).json({
    //     status: false,
    //     message: "User type not allowed",
    //   });
    // }
    columnsToUpdate["user_type"] = req.body.user_type;
  }
  if (req.body.is_active === 0 || req.body.is_active) {
    columnsToUpdate["is_active"] = req.body.is_active;
  }
  if (req.body.is_loggedin === 0 || req.body.is_loggedin) {
    columnsToUpdate["is_loggedin"] = req.body.is_loggedin;
  }
  userModel
    .update(columnsToUpdate, {
      where: {
        id: req.body.id,
      },
    })
    .then((updateUserRes) => {
      return res.status(200).json({
        status: true,
        message: "User updated",
      });
    })
    .catch((updateUserErr) => {
      console.log("===== updateUserErr ===== ", updateUserErr);
      return res.status(500).json({
        status: false,
        message: "Please try again later",
      });
    });
};

module.exports.getUser = async (req, res) => {
  let query = `
  SELECT um.*, rm.role_name,
  DATE_FORMAT(um.created_on, '%d-%m-%Y %T') AS 'formatted_created_on' 
  FROM users um
  LEFT JOIN role_masters rm ON rm.id = um.user_type
  WHERE um.deleted_on IS NULL;
  `;

  if (req.query.id) {
    query += ` AND id=${req.query.id}`;
  }
  if (req.query.user_type) {
    query += ` AND user_type=${req.query.user_type}`;
  }
  if (req.query.is_loggedin) {
    query += ` AND is_loggedin=${req.query.is_loggedin}`;
  }

  const [userRes, metadata] = await sequelize.query(query);
  return res.status(200).json({
    status: true,
    data: userRes,
  });

  userModel.findAll(whereObj).then((userRes) => {
    return res.status(200).json({
      status: true,
      data: userRes,
    });
  });
};

module.exports.getUserInfo = (req, res) => {
  userModel
    .findOne({
      where: {
        id: req.params.id,
      },
    })
    .then((userData) => {
      if (!userData) {
        return res.status(200).json({
          status: false,
          message: "User does not exist",
        });
      }
      return res.status(200).json({
        status: true,
        data: userData,
      });
    });
};

module.exports.checkPhone = (req, res) => {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.phone) {
    return res.status(400).json({
      status: false,
      message: "mobile number is required",
    });
  }



 

  userModel
    .findOne({
      where: {
        user_phone: req.body.phone,
      },
    })
    .then((userData) => {
      if (!userData) {
        return res.status(200).json({
          status: false,
          message: "Phone number not registered",
        });
      }

 

      var generatedOTP = Math.floor(1000 + Math.random() * 9000);
      // static otp for Mehul's number
      if (req.body.phone === 9004094087 || req.body.phone === "9004094087") {
        generatedOTP = 1234;
      }
      let message = `Dear Customer, ${generatedOTP} is the OTP for your login to Ultra Gas and Energy app. Thanks - Team UGEL`;
 

     var mobile = req.body.phone;

      const axios = require('axios');
let data = JSON.stringify({
  "template_id": "68149892d6fc05710160f782",
  "realTimeResponse": "1",
  "recipients": [
    {
      "mobiles": "91"+mobile,
      "otp": generatedOTP
    }
  ]
});

let config = {
  method: 'post',
  maxBodyLength: Infinity,
  url: 'https://control.msg91.com/api/v5/flow',
  headers: { 
    'authkey': '449291Ar1os6ig1686e2758P1', 
    'Content-Type': 'application/json'
  },
  data : data
};

axios.request(config)
.then((response) => {
  console.log(JSON.stringify(response.data));

  console.log("user login send otp response", response);
          
          userModel
            .update(
              {
                otp: generatedOTP,
                otp_created_on: new Date(),
              },
              {
                where: {
                  id: userData.id,
                },
              }
            )
            .then((updateOTPData) => {
              console.log("===== updateOTPData =====", updateOTPData);
            });
         return res.status(200).json({
            status: true,
            message: "OTP sent",
          });
})
.catch((error) => {
  console.log(error);
});



        
    });
};

module.exports.checkOtp = (req, res) => {
  if (Object.keys(req.body).length === 0) {
    return res.status(400).json({
      status: false,
      message: "body required",
    });
  }
  if (!req.body.phone) {
    return res.status(400).json({
      status: false,
      message: "mobile number is required",
    });
  }
  if (!req.body.otp) {
    return res.status(400).json({
      status: false,
      message: "OTP is required",
    });
  }
  if (req.body.otp.length !== 4) {
    return res.status(400).json({
      status: false,
      message: "Please enter valid otp",
    });
  }
  userModel
    .findOne({
      where: {
        user_phone: req.body.phone,
        otp: req.body.otp,
      },
    })
    .then(async (userData) => {
      if (!userData) {
        return res.status(200).json({
          status: false,
          message: "Incorrect OTP",
        });
      }
      // block admin user to login in app
      if (userData.user_type === "admin" && req.body.deviceType === "mobile") {
        res.status(400).json({
          status: false,
          message: "Invalid user",
        });
      }
      // block user to login in web
      if (userData.user_type === "user" && req.body.deviceType === "web") {
        res.status(400).json({
          status: false,
          message: "Invalid user",
        });
      }
      // if (userData.is_loggedin) {
      //   return res.status(403).json({
      //     status: false,
      //     message: "User already loggedin",
      //   });
      // }
      if (!userData.is_active) {
        return res.status(403).json({
          status: false,
          message: "User inactive, please contact admin",
        });
      }

      let query = `
        SELECT *
        FROM role_masters
        WHERE id=${userData.user_type};
      `;

      const [userRes, metadata] = await sequelize.query(query);

      var generatedToken = jwt.sign(
        {
          id: userData.dataValues.id,
          username: req.body.username,
          user_phone: userData.user_phone,
          user_email: userData.user_email,
          last_login: req.lastLogin,
          user_type: userRes[0].role_access,
          ro_access: userRes[0].ro_access,
        },
        config.jwtSecret,
        {
          expiresIn: "1d",
        }
      );
      LoginHistoryController.loginEntry(
        userData.id,
        userData.username,
        userData.phone_number,
        userData.user_email,
        req.body.usertype ? req.body.usertype : "user"
      );
      userModel
        .update(
          {
            is_loggedin: 1,
          },
          {
            where: {
              id: userData.id,
            },
          }
        )
        .then((updateUserRes) => {
          if (!updateUserRes) {
            return res.status(200).json({
              status: false,
              message: "Please try again after sometime",
            });
          }
          return res.status(200).json({
            status: true,
            message: "Login success",
            data: {
              token: generatedToken,
            },
          });
        })
        .catch((updateUserErr) => {
          console.log("======== updateUserErr ========");
          console.log(updateUserErr);
          return res.status(200).json({
            status: false,
            message: "Please try again after sometime",
          });
        });
    });
};
