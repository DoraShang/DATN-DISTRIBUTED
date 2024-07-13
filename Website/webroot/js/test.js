function makedir(entries) {
  const systems = entries.map(entry => traverse(entry, {}));
  return Promise.all(systems);

  async function traverse(entry, fs) {
    if (entry.isDirectory) {
      fs[entry.name] = {};
      let dirReader = entry.createReader();
      await new Promise((res, rej) => {
    dirReader.readEntries(async entries => {
      for(let e of entries) {
        await traverse(e, fs[entry.name]);
      }
      res();
    }, rej);
      });
    } else if (entry.isFile) {
      await new Promise((res, rej) => {
    entry.file(file => {
      fs[entry.name] = file;
      res();
    }, rej);
      });
    }
  return fs;
  }
};

function checkFile(obj){
    return obj instanceof File;
};

function exactFile(system_trees, relativePath, files){
    for (var i = 0; i < system_trees.length; i++){
        for (var property in system_trees[i]) {
            if (system_trees[i].hasOwnProperty(property)) {
                if (checkFile(system_trees[i][property])){
                    system_trees[i][property]["relativePath"] = relativePath;
                    files.push(system_trees[i][property]);
                } else {
                    files.concat(exactFile([system_trees[i][property]], (typeof relativePath !== 'undefined' && relativePath !== '' ? (relativePath + '/') : '') + property, files));
                }
            }
        }
    }
    return files;
};

function readDropped(dT,_data) {
  const entries = [...dT.items].map(item => {
      return item.webkitGetAsEntry ? item.webkitGetAsEntry() : null;
    })
    .filter(entry => entry);
  if (entries.length) {
    makedir(entries).then(function(system_trees){
        var files = exactFile(system_trees, "", []);
        c(_data, files);
    }).catch(function(){
        var files = dT.files;
        c(_data, files);
    });
  } else {
        var files = dT.files;
        c(_data, files);
  }
};